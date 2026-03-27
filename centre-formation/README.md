# Gestion de Centre de Formation

Une solution web complète et moderne pour la gestion administrative et pédagogique d'un centre de formation, développée avec Laravel 11 et Bootstrap 5.

---

### Authentification & Inscription Initiale
L'accès au portail est sécurisé par un système de rôles. Les nouveaux étudiants peuvent créer un compte et choisir leur première formation directement.

| Page de Connexion | Inscription Initiale |
| :---: | :---: |
| ![Page de Connexion](public/screenshots/login_page.png) | ![Page d'Inscription](public/screenshots/register_page.png) |
| *Accès sécurisé pour Admin, Formateurs et Étudiants.* | *Formulaire d'accueil pour les nouveaux étudiants.* |

---
### Espace Administrateur (Gestion Centrale)
L'administrateur supervise l'intégralité du centre, des inscriptions aux finances.

![Dashboard Admin](public/screenshots/admin_dashboard.png)
*Vue globale avec statistiques de fréquentation, répartition par formation et graphes de revenus mensuels.*

![Inscriptions](public/screenshots/admin_inscriptions.png)
*Validation ou refus des demandes d'inscriptions entrantes avec gestion automatisée des places.*

| Gestion Formateurs | Gestion Étudiants |
| :---: | :---: |
| ![Formateurs](public/screenshots/admin_formateurs.png) | ![Étudiants](public/screenshots/admin_etudiants.png) |
| *Gestion du personnel enseignant et de leurs spécialités.* | *Liste des étudiants inscrits avec filtres de recherche.* |

| Gestion Paiements | Gestion Sessions |
| :---: | :---: |
| ![Paiements](public/screenshots/admin_paiements.png) | ![Sessions](public/screenshots/admin_sessions.png) |
| *Suivi comptable de toutes les transactions du centre.* | *Planification des cours (matières, horaires, jours de la semaine).* |

---

### Espace Étudiant (Suivi & Nouvelles Inscriptions)
Une fois connecté, l'étudiant dispose d'un tableau de bord complet pour suivre ses cours, ses notes et ses paiements. Il peut également s'inscrire à de nouvelles formations supplémentaires.

![S'inscrire à une nouvelle formation](public/screenshots/student_new_enrollment.png)
*Interface interne permettant à un étudiant déjà inscrit de postuler à d'autres formations du catalogue.*

![Dashboard Étudiant](public/screenshots/student_dashboard.png)
*Résumé du profil, état des inscriptions (En attente/Validé) et accès rapide aux services.*

| Mon Planning | Mes Notes |
| :---: | :---: |
| ![Planning Étudiant](public/screenshots/student_planning.png) | ![Mes Notes](public/screenshots/student_notes.png) |
| *Calendrier unifié affichant les sessions de toutes les formations validées.* | *Relevé de notes détaillé avec calcul automatique de la moyenne par formation.* |

![Mes Paiements](public/screenshots/student_paiements.png)
*Historique complet des transactions financières et suivi du solde restant.*

---

### Espace Formateur (Pédagogie)
Le formateur gère ses classes, ses plannings de cours et l'évaluation de ses étudiants.

![Dashboard Formateur](public/screenshots/formateur_dashboard.png)
*Aperçu des formations assignées et des statistiques de ses classes.*

| Liste des Étudiants | Saisie des Notes |
| :---: | :---: |
| ![Liste Étudiants](public/screenshots/formateur_etudiants.png) | ![Saisie Notes](public/screenshots/formateur_notes.png) |
| *Visualisation des étudiants validés pour chaque formation.* | *Interface rapide pour l'attribution des notes de fin de module.* |

![Planning Formateur](public/screenshots/formateur_planning.png)
*Calendrier personnel indiquant les créneaux horaires de ses interventions.*

---

## Identifiants de Test (Mode Démo)

| Rôle | Email | Mot de passe |
| :--- | :--- | :--- |
| **Admin** | `admin@centre.ma` | `admin123` |
| **Formateur (Hamid)** | `hamid@centre.ma` | `123456` |
| **Formateur (Hajar)** | `hajar@centre.ma` | `123456` |
| **Formateur (Houda)** | `houda@centre.ma` | `123456` |
| **Étudiant** | *S'inscrire via le formulaire* | *Votre mot de passe* |

---

## Fonctionnalités Clés

L'application regroupe l'ensemble des fonctionnalités nécessaires à la gestion moderne d'un centre de formation, réparties selon 3 rôles distincts.

### 🛡️ Administration (Admin)
- **Tableau de bord dynamique** : Vue d'ensemble en temps réel (inscriptions, revenus mensuels, nombre d'étudiants réels validés).
- **Gestion des Formations** : Création, description, durée, tarification et définition du quota de places maximum.
- **Système Automatisé des Places** : Décrémentation automatique des places lors de la création d'une inscription et libération automatique en cas de refus ou suppression.
- **Gestion des Inscriptions** : Modération des demandes entrantes avec changement de statut (Validé, Refusé, En attente).
- **Trésorerie et Comptabilité** : Enregistrement et suivi des versements, modes de paiement, montants réglés.
- **Gestion des Sessions & Plannings** : Affectation des séminaires aux formateurs avec définition des créneaux horaires, calcul des durées, et gestion précise des jours de la semaine.
- **Gestion du Personnel et des Élèves** : Création d'étudiants ou de formateurs en back-office avec envoi instantané et automatique de leurs identifiants sécurisés par email.

### 👨‍🏫 Pédagogie (Formateur)
- **Emploi du temps personnalisé** : Planning des sessions affichant uniquement les horaires, classes et matières qui lui sont assignées.
- **Suivi des Classes** : Vue détaillée de la liste des étudiants officiellement validés et inscrits à ses formations.
- **Saisie et Édition des Notes** : Interface dédiée pour évaluer ses étudiants avec un système de contrôle (interdiction d'évaluer un élève non validé).

### 🎓 Apprentissage (Étudiant)
- **Portail d'Inscription Public** : Formulaire fluide permettant aux nouveaux candidats de créer leur compte et choisir leur première formation.
- **Catalogue Interne (Multi-Inscriptions)** : Espace interne simplifié permettant la candidature instantanée à d'autres formations supplémentaires.
- **Suivi Académique** : Relevé de notes détaillé par matière et calcul automatique des moyennes globales.
- **Historique Financier** : Transparence sur les paiements effectués et les versements en attente.
- **Planning Global** : Synthèse de la totalité de ses horaires de cours regroupant l'ensemble de ses formations actives.

### ⚙️ Technique & Architecture Transverse
- **Notifications Email Intégrées (SMTP/Mailpit)** : Un système robuste d'alertes automatiques qui notifie instantanément les acteurs :
  - **Identifiants & Inscription** : L'étudiant reçoit son compte, est alerté du changement de statut de sa candidature ou si son **affectation à une formation est modifiée** par l'admin.
  - **Alertes Académiques** : L'étudiant est notifié dès qu'un formateur lui attribue une nouvelle note.
  - **Gestion des Plannings (Nouveauté)** : **Le formateur et les étudiants** sont alertés lors de la **programmation d'une session** ou de sa **modification**. Le système identifie intelligemment les champs modifiés (date, heure, formateur) pour les préciser dans l'email.
- **Sécurité Multi-Guards** : Barrières d'authentification Laravel (Middlewares) séparant strictements les sessions (un étudiant ne peut accéder à l'URL d'un formateur).
- **Protection des Données** : Mots de passe hachés (Bcrypt) et protection contre les failles CSRF sur tous les formulaires.
- **Dockerisation Complète** : Conteneurs de développement et de base de données (MySQL) gérés dynamiquement via Laravel Sail.
- **Design UI/UX Premium** : Interface entièrement responsive (Bootstrap 5 + Custom CSS), utilisant des palettes de couleurs harmonieuses, des icônes indicatives, un logo personnalisé dynamique, et des animations pour garantir une expérience utilisateur.


---

## Installation

1. **Cloner le dépôt** :
   ```bash
   git clone <repository-url>
   cd centre-formation
   ```

2. **Installer les dépendances** :
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Configuration de l'environnement** :
   - Copier `.env.example` en `.env`
   - Configurer votre base de données dans le fichier `.env`
   - Générer la clé d'application :
     ```bash
     php artisan key:generate
     ```

4. **Migration et Seeders** :
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Lancer le serveur** :
   ```bash
   php artisan serve
   ```

---

## Exécution avec Docker (Laravel Sail)

Si vous préférez utiliser Docker, le projet est déjà configuré avec **Laravel Sail**.

1. **Lancer les conteneurs** :
   ```bash
   ./vendor/bin/sail up -d
   ```

2. **Exécuter les migrations et seeders** (première fois) :
   ```bash
   ./vendor/bin/sail artisan migrate:fresh --seed
   ```

3. **Accès à l'application** :
   - Application : `http://localhost`
   - Mailpit (Dashboard Mail) : `http://localhost:8025`

4. **Arrêter l'environnement** :
   ```bash
   ./vendor/bin/sail stop
   ```

---

Développé dans le cadre d'un projet de stage.
