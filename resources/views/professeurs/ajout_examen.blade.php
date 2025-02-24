@extends('layouts.templateProfesseur')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Créer un Examen</h2>

        <form>
            @csrf

            <!-- Sélection de la matière -->
            <div class="mb-3">
                <label for="matiere" class="form-label">Matière</label>
                <select class="form-select" id="matiere" required>
                    <option value="" disabled selected>Choisir la matière</option>
                    <option>Mathématiques</option>
                    <option>Physique</option>
                    <option>Histoire</option>
                </select>
            </div>

            <!-- Sélection de la filière -->
            <div class="mb-3">
                <label for="filiere" class="form-label">Filière</label>
                <select class="form-select" id="filiere" required>
                    <option value="" disabled selected>Choisir une filière</option>
                    <option>Informatique</option>
                    <option>Économie</option>
                    <option>Sciences</option>
                </select>
            </div>

            <!-- Vérification si le professeur est responsable -->
            <div class="mb-3">
                <label class="form-label">Êtes-vous responsable de cette filière ?</label>
                <div>
                    <input type="radio" id="responsableOui" name="responsable" value="oui">
                    <label for="responsableOui">Oui</label>

                    <input type="radio" id="responsableNon" name="responsable" value="non" checked>
                    <label for="responsableNon">Non</label>
                </div>
            </div>

            <!-- Sélection des classes/élèves en fonction de la responsabilité -->
            <div id="choixClassesElèves">
                <!-- Pour un responsable -->
                <div id="responsableOptions" style="display: none;">
                    <label for="choixClasse" class="form-label">Choisir les classes ou élèves</label>
                    <div>
                        <input type="radio" id="toutePromo" name="choix" value="toutePromo">
                        <label for="toutePromo">Toute la promotion</label>
                    </div>
                    <div>
                        <input type="radio" id="classesSpecifiques" name="choix" value="classesSpecifiques">
                        <label for="classesSpecifiques">Classes spécifiques</label>
                        <select class="form-select mt-2" id="classesSelect">
                            <option>Classe A</option>
                            <option>Classe B</option>
                            <option>Classe C</option>
                        </select>
                    </div>
                    <div>
                        <input type="radio" id="elevesSpecifiques" name="choix" value="elevesSpecifiques">
                        <label for="elevesSpecifiques">Élèves spécifiques</label>
                        <select class="form-select mt-2" multiple>
                            <option>Jean Dupont</option>
                            <option>Marie Curie</option>
                            <option>Albert Einstein</option>
                        </select>
                    </div>
                </div>

                <!-- Pour un non-responsable -->
                <div id="nonResponsableOptions" style="display: none;">
                    <label for="choixClasse" class="form-label">Choisir votre classe ou élèves</label>
                    <select class="form-select" id="classeSelect">
                        <option>Classe A</option>
                        <option>Classe B</option>
                        <option>Classe C</option>
                    </select>

                    <label for="elevesSelect" class="form-label mt-2">Choisir les élèves</label>
                    <select class="form-select" multiple id="elevesSelect">
                        <option>Jean Dupont</option>
                        <option>Marie Curie</option>
                        <option>Albert Einstein</option>
                    </select>
                </div>
            </div>

            <!-- Date de l'examen -->
            <div class="mb-3">
                <label for="dateExamen" class="form-label">Date de l'examen</label>
                <input type="date" class="form-control" id="dateExamen" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Créer Examen</button>
            </div>
        </form>
    </div>

    <!-- Modal pour choisir une classe spécifique -->
    <div class="modal fade" id="classeModal" tabindex="-1" aria-labelledby="classeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="classeModalLabel">Choisir la classe spécifique</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="modalClasseSelect" class="form-label">Sélectionner une classe spécifique</label>
                    <select class="form-select" id="modalClasseSelect">
                        <option>Classe A</option>
                        <option>Classe B</option>
                        <option>Classe C</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="selectClasseBtn">Sélectionner</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Afficher les options selon si le professeur est responsable ou non
        document.querySelectorAll('input[name="responsable"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const isResponsable = document.getElementById('responsableOui').checked;
                const responsableOptions = document.getElementById('responsableOptions');
                const nonResponsableOptions = document.getElementById('nonResponsableOptions');

                if (isResponsable) {
                    responsableOptions.style.display = 'block';
                    nonResponsableOptions.style.display = 'none';
                } else {
                    responsableOptions.style.display = 'none';
                    nonResponsableOptions.style.display = 'block';
                }
            });
        });

        // Afficher le modal si "Classes spécifiques" est sélectionné
        document.getElementById('classesSpecifiques').addEventListener('change', function() {
            if (this.checked) {
                const modal = new bootstrap.Modal(document.getElementById('classeModal'));
                modal.show();
            }
        });

        // Logique de la sélection de classe dans le modal
        document.getElementById('selectClasseBtn').addEventListener('click', function() {
            const selectedClass = document.getElementById('modalClasseSelect').value;
            document.getElementById('classesSelect').value = selectedClass;
            const modal = bootstrap.Modal.getInstance(document.getElementById('classeModal'));
            modal.hide();
        });
    </script>
@endsection
