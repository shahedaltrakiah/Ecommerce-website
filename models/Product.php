<?php

require 'models/Model.php';

class Product extends Model
{
    public function __construct()
    {
        parent::__construct('products');
    }

    // Method to get all products with their category names and first image
    public function getProducts()
    {
        $statement = $this->pdo->prepare("
            SELECT p.*, 
                   (SELECT pi.image_url 
                    FROM productimages pi 
                    WHERE pi.product_id = p.id 
                    LIMIT 1) AS image_url, 
                   c.category_name 
            FROM $this->table p 
            JOIN categories c ON p.category_id = c.id
        ");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to get main categories and their products with primary images
    public function getMainCategoriesWithProducts()
    {
        $statement = $this->pdo->prepare("
            SELECT c.id AS category_id, 
                   c.category_name, 
                   c.image_url AS category_image_url,
                   GROUP_CONCAT(p.id) AS product_id,
                   GROUP_CONCAT(p.product_name) AS product_names,
                   GROUP_CONCAT(p.price) AS product_prices,
                   GROUP_CONCAT((SELECT pi.image_url 
                                 FROM productimages pi 
                                 WHERE pi.product_id = p.id 
                                 LIMIT 1)) AS product_images
            FROM categories c
            LEFT JOIN products p ON p.category_id = c.id
            GROUP BY c.id
            ORDER BY c.category_name ASC, COUNT(p.id) DESC
        ");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to get products by category ID with first image and category name
    public function getProductCategory($categoryId)
    {
        $statement = $this->pdo->prepare("
            SELECT 
                c.category_name,
                p.*,
                (SELECT pi.image_url 
                 FROM productimages pi 
                 WHERE pi.product_id = p.id 
                 LIMIT 1) AS image_url
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE c.id = :category_id
        ");
        $statement->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to get products by pagination and filtering with primary image
    public function getProductsByPage($search = '', $min_price = 0, $max_price = 1000, $category_id = null, $currentPage = 1, $itemsPerPage = 10)
    {
        $offset = ($currentPage - 1) * $itemsPerPage;
        $sql = "
            SELECT p.*, 
                   (SELECT pi.image_url 
                    FROM productimages pi 
                    WHERE pi.product_id = p.id 
                    LIMIT 1) AS image_url 
            FROM products p
            WHERE p.price BETWEEN :min_price AND :max_price";

        // Apply search and category filters
        if ($search) {
            $sql .= " AND p.product_name LIKE :search";
        }
        if ($category_id) {
            $sql .= " AND p.category_id = :category_id";
        }

        $sql .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':min_price', $min_price);
        $stmt->bindValue(':max_price', $max_price);
        $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        if ($search) {
            $stmt->bindValue(':search', '%' . $search . '%');
        }
        if ($category_id) {
            $stmt->bindValue(':category_id', $category_id);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to count total products with filtering
    public function getProductCount($search = '', $min_price = 0, $max_price = 1000, $category_id = null)
    {
        $sql = "SELECT COUNT(*) FROM products WHERE price BETWEEN :min_price AND :max_price";

        // Add search and category filters
        if ($search) {
            $sql .= " AND product_name LIKE :search";
        }
        if ($category_id) {
            $sql .= " AND category_id = :category_id";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':min_price', $min_price);
        $stmt->bindValue(':max_price', $max_price);

        if ($search) {
            $stmt->bindValue(':search', '%' . $search . '%');
        }
        if ($category_id) {
            $stmt->bindValue(':category_id', $category_id);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Method to get all categories
    public function getCategories()
    {
        $stmt = $this->pdo->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // New method to get a product by ID
    public function getProductById($productId)
    {
        $statement = $this->pdo->prepare("
            SELECT p.*, c.category_name, 
                   (SELECT GROUP_CONCAT(image_url) 
                    FROM productimages 
                    WHERE product_id = p.id) AS all_images
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.id = :product_id
        ");
        $statement->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }
public function getProductByIdWithSingleImage($productId)
{
    $statement = $this->pdo->prepare("
        SELECT p.*, c.category_name, 
               (SELECT image_url 
                FROM productimages 
                WHERE product_id = p.id 
                LIMIT 1) AS image
        FROM products p
        JOIN categories c ON p.category_id = c.id
        WHERE p.id = :product_id
    ");
    $statement->bindParam(':product_id', $productId, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetch(\PDO::FETCH_ASSOC);
    
}



    //rania function fro product_details/  New: Add review to a product
    public function addReview($productId, $fullName, $email, $phone, $rating, $comment)
    {
        $statement = $this->pdo->prepare("
            INSERT INTO reviews (product_id, full_name, email, phone, rating, comment, created_at) 
            VALUES (:product_id, :full_name, :email, :phone, :rating, :comment, NOW())
        ");
        $statement->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $statement->bindParam(':full_name', $fullName, PDO::PARAM_STR);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->bindParam(':phone', $phone, PDO::PARAM_STR);
        $statement->bindParam(':rating', $rating, PDO::PARAM_INT);
        $statement->bindParam(':comment', $comment, PDO::PARAM_STR);
        return $statement->execute();
    }
}