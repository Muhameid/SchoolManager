<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        // Création de la fonction verif_filiere_matiere
        $fonction = "CREATE OR REPLACE FUNCTION verif_filiere_matiere()
        RETURNS trigger AS 
        $$ 
        DECLARE
            existe boolean;
        BEGIN
            existe:=FALSE;

            SELECT TRUE INTO existe
            WHERE EXISTS (
                SELECT * 
                FROM professeur_matiere 
                WHERE professeur_id = NEW.professeur_id
                AND matiere_id = NEW.matiere_id
            );

            IF existe <> TRUE OR existe IS NULL THEN 
                RAISE EXCEPTION 'Prof non trouvé';
            END IF;

            existe := FALSE;

            SELECT TRUE INTO existe
            WHERE EXISTS (
                SELECT * 
                FROM filiere_matiere fm
                JOIN filieres f on (fm.filiere_id = f.id)
                JOIN classes c on (c.filiere_id = f.id)
                WHERE fm.matiere_id = NEW.matiere_id
                AND c.id = NEW.classe_id
            );

            IF existe <> TRUE OR existe IS NULL THEN 
                RAISE EXCEPTION 'Matière non trouvée';
            END IF;

            RETURN NEW;
        END
        $$ LANGUAGE 'plpgsql';";

        DB::statement($fonction);

        // Création du trigger verif_filiere_matiere_trigger
        $trigger = "CREATE OR REPLACE TRIGGER verif_filiere_matiere_trigger
        BEFORE INSERT OR UPDATE ON classe_matiere
        FOR EACH ROW EXECUTE PROCEDURE verif_filiere_matiere()";

        DB::statement($trigger);

        // Fonction et Trigger pour la suppression d'un professeur
        $fonction = "CREATE OR REPLACE FUNCTION verif_suppression_professeur()
        RETURNS trigger AS 
        $$ 
        DECLARE
            existe boolean;
        BEGIN
            existe := FALSE;

            SELECT TRUE INTO existe
            WHERE EXISTS (
                SELECT * FROM classe_matiere
                WHERE professeur_id = OLD.professeur_id
                AND matiere_id = OLD.matiere_id
            );

            IF existe = TRUE THEN 
                RAISE EXCEPTION 'Le professeur est associé à une classe';
            END IF;

            existe := FALSE;

            SELECT TRUE INTO existe
            WHERE EXISTS (
                SELECT * FROM filiere_matiere
                WHERE professeur_id = OLD.professeur_id
                AND matiere_id = OLD.matiere_id
            );

            IF existe = TRUE THEN 
                RAISE EXCEPTION 'Le professeur est responsable de filière';
            END IF;

            existe := FALSE;

            SELECT TRUE INTO existe
            WHERE EXISTS (
                SELECT * FROM classe_option
                WHERE professeur_id = OLD.professeur_id
                AND matiere_id = OLD.matiere_id
            );

            IF existe = TRUE THEN 
                RAISE EXCEPTION 'Le professeur est associé à une classe option';
            END IF;

            existe := FALSE;

            SELECT TRUE INTO existe
            WHERE EXISTS (
                SELECT * FROM examens
                WHERE professeur_id = OLD.professeur_id
                AND matiere_id = OLD.matiere_id
            );

            IF existe = TRUE THEN
                RAISE EXCEPTION 'Le professeur ne peut pas être supprimé, il est affilié à un ou plusieurs examens.';
            END IF;

            RETURN OLD;
        END
        $$ LANGUAGE 'plpgsql';";

        DB::statement($fonction);

        // Trigger pour la suppression d'un professeur
        $trigger = "CREATE OR REPLACE TRIGGER verif_suppression_professeur_trigger
        BEFORE DELETE ON professeur_matiere
        FOR EACH ROW EXECUTE PROCEDURE verif_suppression_professeur()";

        DB::statement($trigger);

        // Création de la fonction verif_professeur_matiere
        $fonction = "CREATE OR REPLACE FUNCTION verif_professeur_matiere()
        RETURNS trigger AS 
        $$ 
        DECLARE
            existe boolean;
        BEGIN
            IF NEW.professeur_id IS NOT NULL THEN
                existe := NULL;

                SELECT TRUE INTO existe
                WHERE EXISTS (
                    SELECT * 
                    FROM professeur_matiere
                    WHERE professeur_id = NEW.professeur_id
                    AND matiere_id = NEW.matiere_id
                );

                IF existe IS NULL THEN 
                    RAISE EXCEPTION 'Le prof ne peut pas enseigner cette matière';
                END IF;
            END IF;

            RETURN NEW;
        END
        $$ LANGUAGE 'plpgsql';";

        DB::statement($fonction);

        // Création des triggers pour la vérification des professeurs
        $trigger = "CREATE OR REPLACE TRIGGER verif_professeur_matiere_trigger
        BEFORE INSERT OR UPDATE ON classe_options
        FOR EACH ROW EXECUTE PROCEDURE verif_professeur_matiere();

        CREATE OR REPLACE TRIGGER verif_professeur_matiere_filiere_trigger
        BEFORE INSERT OR UPDATE ON filiere_matiere
        FOR EACH ROW EXECUTE PROCEDURE verif_professeur_matiere();";

        DB::statement($trigger);

        // Fonction et Trigger pour la suppression d'examen
        $fonction = "CREATE OR REPLACE FUNCTION verif_suppression_examen()
        RETURNS trigger AS 
        $$ 
        DECLARE
            existe boolean;
        BEGIN
            existe := FALSE;

            SELECT TRUE INTO existe
            WHERE EXISTS (
                SELECT * FROM eleve_examen
                WHERE examen_id = OLD.id
            );

            IF existe = TRUE THEN 
                RAISE EXCEPTION 'Examen associé à au moins un élève';
            END IF;

            RETURN OLD;
        END
        $$ LANGUAGE 'plpgsql';";

        DB::statement($fonction);

        $trigger = "CREATE OR REPLACE TRIGGER verif_suppression_examen_trigger
        BEFORE DELETE ON examens
        FOR EACH ROW EXECUTE PROCEDURE verif_suppression_examen()";

        DB::statement($trigger);

        // Fonction et Trigger pour la suppression d'un élève
        $fonction = "CREATE OR REPLACE FUNCTION verif_suppression_eleve()
        RETURNS trigger AS 
        $$ 
        DECLARE
            existe boolean;
        BEGIN
            existe := FALSE;

            SELECT TRUE INTO existe
            WHERE EXISTS (
                SELECT * FROM classe_option_eleve
                WHERE eleve_id = OLD.id
            );

            IF existe = TRUE THEN 
                RAISE EXCEPTION 'Impossible de supprimer cet élève puisqu\'il est inscrit dans une classe option';
            END IF;

            existe := FALSE;

            SELECT TRUE INTO existe
            WHERE EXISTS (
                SELECT * FROM classes
                WHERE classes.id = OLD.classe_id
            );

            IF existe = TRUE THEN 
                RAISE EXCEPTION 'Impossible de supprimer cet élève puisqu\'il est inscrit dans une classe';
            END IF;

            RETURN OLD;
        END
        $$ LANGUAGE 'plpgsql';";

        DB::statement($fonction);

        $trigger = "CREATE OR REPLACE TRIGGER verif_suppression_eleve_trigger
        BEFORE DELETE ON eleves
        FOR EACH ROW EXECUTE PROCEDURE verif_suppression_eleve()";

        DB::statement($trigger);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Supprimer les triggers et fonctions
        $triggersAndFunctions = [
            ['verif_filiere_matiere_trigger', 'verif_filiere_matiere'],
            ['verif_suppression_professeur_trigger', 'verif_suppression_professeur'],
            ['verif_professeur_matiere_trigger', 'verif_professeur_matiere'],
            ['verif_professeur_matiere_filiere_trigger', 'verif_professeur_matiere'],
            ['verif_suppression_examen_trigger', 'verif_suppression_examen'],
            ['verif_suppression_eleve_trigger', 'verif_suppression_eleve']
        ];

        foreach ($triggersAndFunctions as $item) {
            $triggerDrop = 'DROP TRIGGER IF EXISTS ' . $item[0] . ' ON ' . $item[1];
            $fonctionDrop = 'DROP FUNCTION IF EXISTS ' . $item[1] . '()';

            DB::statement($triggerDrop);
            DB::statement($fonctionDrop);
        }
    }
};
