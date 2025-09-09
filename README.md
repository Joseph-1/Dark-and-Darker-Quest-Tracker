# 🎮 Dark and Darker - Quest Tracker

Dark and Darker - Quest Tracker est une application web conçue pour aider les joueurs à suivre et gérer leurs quêtes, objets et marchands dans le jeu Dark and Darker. 
Grâce à une interface intuitive et des outils de suivi automatisés, les utilisateurs peuvent visualiser leur progression, gérer leurs quêtes et optimiser leur inventaire en temps réel.

Cette application met en œuvre des fonctionnalités complètes de gestion de contenu, d’authentification sécurisée et de suivi personnalisé, tout en offrant une expérience utilisateur fluide et moderne.

---

## ✨ Fonctionnalités principales

- Gestion des quêtes, objets et marchands :  création, modification, suppression et consultation.
- Système de progression utilisateur :  suivi et incrémentation des objets requis pour chaque quête.
- Calcul automatique des totaux d’objets requis (par rareté et par type d’objet) pour donner une vue synthétique.
- Upload et gestion d’images pour enrichir les contenus.
- Navigation fluide grâce à la pagination pour explorer quêtes, objets et marchands.

- Authentification & sécurité :
    - Inscription avec confirmation par email (token unique).
    - Connexion sécurisée.
    - Réinitialisation du mot de passe via email (token unique).
    
- Gestion des rôles et permissions :  accès restreint aux fonctionnalités sensibles (réservées aux administrateurs ou contributeurs).

---

## ⚙️ Aspects techniques

- Architecture & qualité
    - Séparation de la logique métier dans des services dédiés pour une meilleure maintenabilité.
    - Validation côté serveur avec le composant Symfony Validator sur l’ensemble des formulaires.
    - Création d’une extension Twig personnalisée permettant de mettre en avant certains mots-clés (exemple :  rareté des objets) avec un style dynamique.
    - Ajout d'un style automatique en fonction du nom de la map affichée.
    
- Expérience utilisateur
    - TailwindCSS pour un design responsive et moderne.
    - KnpPaginatorBundle pour une pagination fluide sur les listes de quêtes, marchands et objets.
    
- Gestion des médias
    - Upload et gestion des images via VichUploaderBundle.
    
- Logique métier spécifique
    - Service pour l’incrémentation/décrémentation des objets liés à une quête.
    - Service de comptage des objets de quête totaux.
    - Service d'envoi d'email.
    
---

## 🛠️ Technologies utilisées

**Frontend :**
- Twig (templating)
- Tailwind CSS (UI / responsive)
- Sass (préprocesseur CSS)
- JavaScript (manipulation DOM pour formulaires dynamiques)

**Backend :**
- Php 8.3
- Symfony 7
- Doctrine ORM

**Base de données :**
- MySQL 8 (base de données)
- PhpMyAdmin (administration de MySQL)

**Bundles :**
- KnpPaginatorBundle (pagination)
- VichUploaderBundle (upload d’images)
- MailHog (envoi d'email)

**Autres :**
- Git (versioning)

---

## 🖼️ Capture d’écran / Vidéo courte


### 🔹 Fonctionnalité clé : suivi des objets de quête


https://github.com/user-attachments/assets/cae9f2ca-5c1c-4a86-a2d1-291fa5df4739


---

### 🔹 Aperçu de l’application

**Liste des quêtes avec pagination**
<img width="1854" height="2053" alt="liste-quetes" src="https://github.com/user-attachments/assets/1dcf7ee0-400b-4338-af5f-00461bb2c150" />

**Création / édition d’une quête**
<img width="1854" height="1421" alt="creation-quete" src="https://github.com/user-attachments/assets/c9095bbd-c13a-48f1-a7d4-1251981c222d" />
<img width="1854" height="1519" alt="edition-quete" src="https://github.com/user-attachments/assets/f1fbc42b-31b3-44a9-8140-a066a69e976b" />

**Connexion et inscription utilisateur**
<img width="1849" height="805" alt="register-page" src="https://github.com/user-attachments/assets/a82b4ba3-bae3-4384-a524-6a4621f2405e" />
<img width="1849" height="805" alt="login-page" src="https://github.com/user-attachments/assets/d37ad803-7549-4c72-baec-6a99c0f3841e" />

**Liste synthétique du total des objets par rareté***
<img width="1854" height="1356" alt="synth-objet" src="https://github.com/user-attachments/assets/61d0f3ee-860c-4c36-9551-23168cf94248" />

---

## 👀 À venir / Améliorations

- Rechercher et filtrer les quêtes par leur nom, description ou autre filtre avancé.
- Protection CSRF activée sur tous les formulaires.
- Intégrer un compteur d'objets total sur la page synthétique de compteur d'objets total.
- Ajouter un tableau de bord synthétique pour visualiser la progression globale des utilisateurs.
- Ajouter des notifications en temps réel lors de l’incrémentation d’objets.
- Tests unitaires et fonctionnels pour valider la logique métier et les services.
---

Réalisé avec ❤️ par [Joseph](https://github.com/Joseph-1)
