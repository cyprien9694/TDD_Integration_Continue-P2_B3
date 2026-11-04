# TDD & Intégration Continue - Projet Laravel

Projet développé dans le cadre du cursus Bachelor (B3) en ingénierie logicielle, mettant en œuvre une démarche de Test-Driven Development (TDD) et un pipeline d'intégration continue automatisé.

## 📋 Fonctionnalités & Composants testés

Le projet comprend une suite de tests robuste couvrant :
* **Tests Unitaires (`tests/Unit`)** :
  * `UtilsTest` : Validation des algorithmes de tri (élèves par note, nom, âge, etc.).
  * `ValidatorsTest` : Validation rigoureuse des formats d'e-mails, robustesse des mots de passe et contraintes d'âge.
* **Tests Fonctionnels / API (`tests/Feature`)** :
  * `OrderApiTest` : Simulation de commandes, gestion des codes promo, vérification des erreurs (404, restaurants fermés).

---

## ⚙️ Prérequis

Assure-toi d'avoir les éléments suivants installés sur ta machine locale :
* **PHP >= 8.4**
* **Composer**

---

## 🚀 Installation locale

1. **Cloner le dépôt :**
   ```bash
   git clone [https://github.com/cyprien9694/TDD_Integration_Continue-P2_B3.git](https://github.com/cyprien9694/TDD_Integration_Continue-P2_B3.git)
   cd TDD_Integration_Continue-P2_B3
