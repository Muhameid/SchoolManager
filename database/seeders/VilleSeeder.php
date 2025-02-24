<?php
namespace Database\Seeders;
use App\Models\Ville;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VilleSeeder extends Seeder
{
    public function run()
    {
        // Chemin vers votre fichier CSV
        $csvFile = public_path('ville_monde.csv');
        
        // Ouvrir le fichier CSV en mode lecture
        if (($handle = fopen($csvFile, 'r')) !== false) {
            // Ignorer la première ligne si elle contient les en-têtes
            fgetcsv($handle);
            $pays = DB::table('pays')->get();
            // Parcourir chaque ligne du fichier CSV
            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                $id= trim($data[0]);
                $nom= trim(utf8_encode($data[1]));
                $code_pays= strtoupper( trim($data[2]));
                $code_ville= trim($data[3]);
                if(preg_match("/^[0-9]*$/",$id) && preg_match("/^[0-9]*$/",$code_ville) && strlen($nom)>0 && strlen($nom)<=70)
                {
                    $toto= $pays->firstWhere('code', $code_pays);
                    if (is_object($toto) ){
                        

                        Ville::insertOrIgnore([
                            'id' => $id, // Exemple : 3036053
                            'nom' => $nom, // Exemple : Aureil
                            'pays_id' => $toto->id, // Exemple : FR
                            'code_ville' => $code_ville, // Exemple : 87005
                        ]);
                        
                    }

                    
                // Insérer les données dans la table "locations"
                    
                }
            }

            // Fermer le fichier CSV
            fclose($handle);
        }
    }

}
