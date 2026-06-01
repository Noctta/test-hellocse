# HelloCSE API

API RESTful de gestion de produits et catégories, développée avec **Laravel 12** et **PHP 8.2+**.

---

## Stack technique

- **Framework** : Laravel 12
- **Langage** : PHP 8.2+ (enums, constructor promotion, named arguments, typed properties)
- **Base de données** : SQLite
- **Tests** : Pest

---

## Installation

```bash
# 1. Cloner le projet
git clone https://github.com/Noctta/test-hellocse.git
cd test-hellocse

# 2. Installer les dépendances
composer install

# 3. Copier et configurer l'environnement
cp .env.example .env
php artisan key:generate

# 4. Lancer les migrations et le seeding
php artisan migrate --seed

# 5. Lancer le serveur
php artisan serve
```

L'API est accessible sur `http://127.0.0.1:8000/api` (ou éventuellement sur le port 8001).

---

## Architecture du projet

```
app/
├── Enums/
│   ├── CategoryStatus.php        # Enum : online, disabled, archived
│   └── ProductStatus.php         # Enum : online, draft, disabled
├── Http/
│   ├── Controllers/Api/
│   │   ├── CategoryController.php
│   │   └── ProductController.php
│   ├── Middleware/
│   │   └── ForceJsonResponse.php 
│   ├── Requests/
│   │   ├── StoreCategoryRequest.php
│   │   ├── UpdateCategoryRequest.php
│   │   ├── StoreProductRequest.php
│   │   └── UpdateProductRequest.php
│   └── Resources/
│       ├── CategoryResource.php
│       ├── CategoryCollection.php
│       ├── ProductResource.php
│       └── ProductCollection.php
├── Models/
│   ├── Category.php
│   └── Product.php
└── Services/
    ├── CategoryService.php       
    └── ProductService.php        
database/
├── factories/
│   ├── CategoryFactory.php
│   └── ProductFactory.php
└── seeders/
    └── DatabaseSeeder.php

tests/Feature/Api/
├── CategoryApiTest.php           
└── ProductApiTest.php            
```


## Endpoints

### Catégories

| Méthode | URI                       | Description                                          |
|---------|---------------------------|------------------------------------------------------|
| GET     | `/api/categories`         | Liste des catégories (avec nombre de produits en ligne) |
| POST    | `/api/categories`         | Créer une catégorie                                  |
| GET     | `/api/categories/{id}`    | Détail d'une catégorie (avec nombre de produits en ligne) |
| PUT     | `/api/categories/{id}`    | Mettre à jour une catégorie                          |
| DELETE  | `/api/categories/{id}`    | Supprimer une catégorie                              |

### Produits

| Méthode | URI                                  | Description                              |
|---------|--------------------------------------|------------------------------------------|
| GET     | `/api/products`                      | Liste des produits                       |
| GET     | `/api/products?category_id={id}`     | Liste des produits filtrés par catégorie |
| POST    | `/api/products`                      | Créer un produit                         |
| GET     | `/api/products/{id}`                 | Détail d'un produit                      |
| PUT     | `/api/products/{id}`                 | Mettre à jour un produit                 |
| DELETE  | `/api/products/{id}`                 | Supprimer un produit                     |

### Statuts possibles

| Entité    | Valeurs                          |
|-----------|----------------------------------|
| Produit   | `online`, `draft`, `disabled`    |
| Catégorie | `online`, `disabled`, `archived` |

---

## Exemples curl

> Assurez-vous que le serveur tourne (`php artisan serve`).

### Catégories

```bash
# Créer une catégorie
curl -X POST http://127.0.0.1:8000/api/categories \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Électronique",
    "status": "online",
    "image": "https://picsum.photos/640/480"
  }'

# Lister les catégories
curl http://127.0.0.1:8000/api/categories

# Détail d'une catégorie (id = 1)
curl http://127.0.0.1:8000/api/categories/1

# Modifier une catégorie
curl -X PUT http://127.0.0.1:8000/api/categories/1 \
  -H "Content-Type: application/json" \
  -d '{"name": "Électronique & High-Tech"}'

# Supprimer une catégorie
curl -X DELETE http://127.0.0.1:8000/api/categories/1
```

### Produits

```bash
# Créer un produit
curl -X POST http://127.0.0.1:8000/api/products \
  -H "Content-Type: application/json" \
  -d '{
    "name": "MacBook Pro 14",
    "price": 1999.99,
    "status": "online",
    "category_id": 1,
    "image": "https://picsum.photos/640/480"
  }'

# Lister les produits
curl http://127.0.0.1:8000/api/products

# Filtrer par catégorie
curl http://127.0.0.1:8000/api/products?category_id=1

# Détail d'un produit (id = 1)
curl http://127.0.0.1:8000/api/products/1

# Modifier un produit
curl -X PUT http://127.0.0.1:8000/api/products/1 \
  -H "Content-Type: application/json" \
  -d '{"name": "MacBook Pro 16", "price": 2499.99}'

# Supprimer un produit
curl -X DELETE http://127.0.0.1:8000/api/products/1
```

---

## Tests

```bash
# Lancer tous les tests
php artisan test
```

---

## Commandes utiles

```bash
# Réinitialiser la base et relancer les seeders
php artisan migrate:fresh --seed

# Lister toutes les routes API
php artisan route:list --path=api

# Ouvrir la console Tinker
php artisan tinker

# Créer le lien symbolique pour les images
php artisan storage:link
```