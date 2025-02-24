<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        $fonction = "CREATE OR REPLACE FUNCTION verif_filiere_matiere()
        RETURNS trigger AS 
      $$
      DECLARE
          existe boolean;
      BEGIN
          existe:=FALSE;
      
          SELECT TRUE into existe
          WHERE EXISTS (
              SELECT * 
              FROM professeur_matiere 
              WHERE professeur_id =     NEW.professeur_id
              AND matiere_id =     NEW.matiere_id
          );
          IF existe <> TRUE OR existe is null THEN 
              RAISE EXCEPTION 'prof non trouve';
          END IF; 
      
          existe:=FALSE;
      
          SELECT TRUE into existe
          WHERE EXISTS (
              SELECT * 
              FROM filiere_matiere fm
              JOIN filieres f on (fm.filiere_id = f.id)
              JOIN classes c on (c.filiere_id = f.id)
              WHERE fm.matiere_id = NEW.matiere_id
              AND c.id = NEW. classe_id
      
          );
          IF existe <> TRUE  OR existe is null   THEN 
              RAISE EXCEPTION 'matiere non trouve';
          END IF; 
          RETURN NEW;
      END
        $$
        LANGUAGE 'plpgsql';";

$trigger = "CREATE OR REPLACE  TRIGGER verif_filiere_matiere_trigger BEFORE INSERT OR UPDATE ON classe_matiere 
FOR EACH ROW EXECUTE PROCEDURE verif_filiere_matiere()";
      DB::statement($fonction);
      DB::statement($trigger);

        $fonction = "CREATE OR REPLACE FUNCTION verif_suppression_professeur()
        RETURNS trigger AS 
        $$
        DECLARE
            existe boolean;
        BEGIN
            existe:=FALSE;

            SELECT TRUE into existe
            WHERE EXISTS (
                SELECT * FROM classe_matiere
                WHERE professeur_id = OLD.professeur_id
                AND matiere_id = OLD.matiere_id
            );
            IF existe = TRUE  THEN 
                RAISE EXCEPTION 'Le professeur est associé à une classe';
            END IF; 


            existe := false;

            SELECT TRUE into existe
            WHERE EXISTS (
                SELECT * FROM filiere_matiere
                WHERE professeur_id = OLD.professeur_id
                AND matiere_id = OLD.matiere_id
            );
            IF existe = TRUE  THEN 
                RAISE EXCEPTION 'Le professeur est responsable de filiere';
            END IF; 

                existe := false;

            SELECT TRUE into existe
            WHERE EXISTS (
                SELECT * FROM classe_option
                WHERE professeur_id = OLD.professeur_id
                AND matiere_id = OLD.matiere_id
            );
            IF existe = TRUE  THEN 
                RAISE EXCEPTION 'Le professeur est associé à une classe option';
            END IF; 

            existe := FALSE;

            SELECT TRUE INTO existe
            WHERE EXISTS (
                SELECT *
                FROM examens
                WHERE professeur_id = OLD.professeur_id
                AND matiere_id = OLD.matiere_id

            );

    IF existe = TRUE THEN
        RAISE EXCEPTION 'Le professeur ne peut pas être supprimé, il est affilié à un ou plusieurs examens.';
    END IF;
    RETURN OLD;

END
$$
  LANGUAGE 'plpgsql';";

  $trigger = "CREATE OR REPLACE TRIGGER verif_suppression_professeur_trigger BEFORE DELETE ON professeur_matiere 
  FOR EACH ROW EXECUTE PROCEDURE verif_suppression_professeur();";
        DB::statement($fonction);
        DB::statement($trigger);

    $fonction ="CREATE OR REPLACE FUNCTION verif_professeur_matiere()
    RETURNS trigger AS 
    $$
    DECLARE
    existe boolean;
    BEGIN
    IF NEW.professeur_id IS NOT NULL THEN
        existe:=NULL;

        SELECT TRUE into existe
        WHERE EXISTS (
            SELECT * 
            FROM professeur_matiere
            WHERE professeur_id = 	NEW.professeur_id
            AND matiere_id = 	NEW.matiere_id		
        );
        IF existe is null THEN 
            RAISE EXCEPTION 'Le prof ne peux pas enseigner cette matiere';
        END IF; 
    END IF;
    RETURN NEW;
    END
    $$
    LANGUAGE 'plpgsql';";

    $trigger="CREATE OR REPLACE TRIGGER verif_professeur_matiere_trigger BEFORE INSERT OR UPDATE ON classe_options 
    FOR EACH ROW EXECUTE PROCEDURE verif_professeur_matiere();
    
    CREATE OR REPLACE TRIGGER verif_professeur_matiere_filiere_trigger BEFORE INSERT OR UPDATE ON filiere_matiere
    FOR EACH ROW
    EXECUTE PROCEDURE verif_professeur_matiere()
    ;";

        DB::statement($fonction);
        DB::statement($trigger);

    $fonction ="CREATE OR REPLACE FUNCTION verif_suppression_examen()
                RETURNS trigger AS 
                $$
                DECLARE
                    existe boolean;
                BEGIN
                    existe:=FALSE;

                    SELECT TRUE into existe
                    WHERE EXISTS (
                        SELECT * FROM eleve_examen
                        WHERE examen_id = OLD.id
                    );
                    IF existe = TRUE  THEN 
                        RAISE EXCEPTION 'Examen associé à au moins un élèves';
                    END IF; 
                    RETURN OLD;

                END
                $$
                LANGUAGE 'plpgsql';";
    $trigger="CREATE OR REPLACE TRIGGER verif_suppression_examen_trigger BEFORE DELETE ON examens 
    FOR EACH ROW EXECUTE PROCEDURE verif_suppression_examen()
    ;";

    $fonction="CREATE OR REPLACE FUNCTION verif_suppression_eleve()
  RETURNS trigger AS 
$$
DECLARE
	existe boolean;
BEGIN
	existe:=FALSE;

	SELECT TRUE into existe
	WHERE EXISTS (
		SELECT * FROM classe_option_eleve
		WHERE eleve_id = OLD.id
	);
	
	IF existe = TRUE  THEN 
		RAISE EXCEPTION 'Impossible de supprimer cet éléve puisqu'il est inscrit dans une classe option.npm';
	END IF; 
	
		existe:=FALSE;

	SELECT TRUE into existe
	WHERE EXISTS (
		SELECT * FROM classes 
		WHERE classes.id = OLD.classe_id
	);
	IF existe = TRUE  THEN 
		RAISE EXCEPTION 'Impossible de supprimer cet éléve puisqu'il est inscrit dans une classe.npm';
	END IF; 
	
	RETURN OLD;

END
$$
  LANGUAGE 'plpgsql';";

    $trigger="CREATE OR REPLACE TRIGGER verif_suppression_eleve_trigger BEFORE DELETE ON eleves
FOR EACH ROW EXECUTE PROCEDURE verif_suppression_eleve()
;";
    
    DB::statement($fonction);
    DB::statement($trigger);

    }

    public function down(){


        $droptrigger = 'DROP TRIGGER IF EXISTS verif_filiere_matiere_trigger ON professeur_matiere;';
        DB::statement ($droptrigger);
        $dropfonction = 'DROP FUNCTION IF EXISTS verif_filiere_matiere();';
        DB::statement ($dropfonction);

        $droptrigger = 'DROP TRIGGER IF EXISTS verif_supression_professeur_trigger ON professeur_matiere;';
        DB::statement ($droptrigger);
        $dropfonction = 'DROP FUNCTION IF EXISTS verif_supression_professeur();';
        DB::statement ($dropfonction);

        $droptrigger = 'DROP TRIGGER IF EXISTS verif_professeur_matiere_trigger ON classe_options;';
        DB::statement ($droptrigger);
        $droptrigger = 'DROP TRIGGER IF EXISTS verif_professeur_matiere_filiere_trigger ON filiere_matiere;';
        DB::statement ($droptrigger);
        $dropfonction = 'DROP FUNCTION IF EXISTS verif_professeur_matiere();';
        DB::statement ($dropfonction);
        
        $droptrigger = 'DROP TRIGGER IF EXISTS verif_suppression_examen_trigger ON examens;';
        DB::statement ($droptrigger);
        $dropfonction = 'DROP FUNCTION IF EXISTS verif_suppression_examen();';
        DB::statement ($dropfonction);

        $droptrigger = 'DROP TRIGGER IF EXISTS verif_suppression_eleve_trigger ON eleves;';
        DB::statement ($droptrigger);
        $dropfonction = 'DROP FUNCTION IF EXISTS verif_suppression_eleve();';
        DB::statement ($dropfonction);

    }
};