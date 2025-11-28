<?php
class Product {
    // Variables que guardan los datos del producto
    private $id;
    private $name;
    private $price;
    private $stock;
    private $category;

    // Variable compartida entre todos los productos para generar IDs automáticos
    private static $nextId = 1;

    // Constructor que se ejecuta cuando se crea un nuevo producto
    public function __construct($id = null, $name = '', $price = 0.0, $stock = 0, $category = 'General') {

        // se crea un id automatico 
        if ($id === null) {
            $this->id = self::$nextId++;   // genera el ID automáticamente
        }        

        // Asignación de valores a las propiedades del producto
        $this->name = $name;
        $this->price = $price;
        $this->stock = $stock;
        $this->category = $category;
    }

    // ---------- leer datoss----------
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getPrice() { return $this->price; }
    public function getStock() { return $this->stock; }
    public function getCategory() { return $this->category; }

    // ---------- cambiar datos ----------
    public function setName($v) { $this->name = $v; }
    public function setPrice($v) { $this->price = (float)$v; }
    public function setStock($v) { $this->stock = (int)$v; }
    public function setCategory($v) { $this->category = $v; }

    // Método que devuelve true si el producto tiene stock
    public function isInStock() {
        return $this->stock > 0;
    }

    // Convierte el objeto en un array para poder guardarlo en JSON
    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'category' => $this->category,
        ];
    }

    // Método que crea un producto usando un array
    public static function createFromArray(array $arr) {
        return new self(
            $arr['id'] ?? null,
            $arr['name'] ?? '',
            $arr['price'] ?? 0,
            $arr['stock'] ?? 0,
            $arr['category'] ?? 'General'
        );
    }
}
