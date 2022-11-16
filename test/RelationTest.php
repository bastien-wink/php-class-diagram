<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

use Smeghead\PhpClassDiagram\Config\Options;
use Smeghead\PhpClassDiagram\DiagramElement\ {
    Entry,
    Relation,
};

require_once(__DIR__ . '/dummy/PhpClassDummy.php');

final class RelationTest extends TestCase {
    private $fixtureDir;
    public function setUp(): void {
    }

    private string $product_expression = <<<EOJ
{
    "type": {
        "name": "Product",
        "meta": "Stmt_Class",
        "namespace": []
    },
    "properties": [
        {
            "name": "name",
            "type": {
                "name": "Name",
                "namespace": []
            },
            "modifier": {
                "private": true
            }
        },
        {
            "name": "price",
            "type": {
                "name": "Price",
                "namespace": []
            },
            "modifier": {
                "private": true
            }
        }
    ],
    "methods":[]
}
EOJ;
    private string $price_expression = <<<EOJ
{
    "type": {
        "name": "Price",
        "meta": "Stmt_Class",
        "namespace": []
    },
    "properties": [
        {
            "name": "price",
            "type": {
                "name": "int",
                "namespace": []
            },
            "modifier": {
                "private": true
            }
        }
    ],
    "methods":[]
}
EOJ;
    private string $name_expression = <<<EOJ
{
    "type": {
        "name": "Name",
        "meta": "Stmt_Class",
        "namespace": []
    },
    "properties": [
        {
            "name": "name",
            "type": {
                "name": "string",
                "namespace": []
            },
            "modifier": {
                "private": true
            }
        }
    ],
    "methods":[]
}
EOJ;

    private string $product_with_tags_expression = <<<EOJ
{
    "type": {
        "name": "Product",
        "meta": "Stmt_Class",
        "namespace": []
    },
    "properties": [
        {
            "name": "name",
            "type": {
                "name": "Name",
                "namespace": []
            },
            "modifier": {
                "private": true
            }
        },
        {
            "name": "price",
            "type": {
                "name": "Price",
                "namespace": []
            },
            "modifier": {
                "private": true
            }
        },
        {
            "name": "tags",
            "type": {
                "name": "Tag[]",
                "namespace": []
            },
            "modifier": {
                "private": true
            }
        }
    ],
    "methods":[]
}
EOJ;
    private string $tag_expression = <<<EOJ
{
    "type": {
        "name": "Tag",
        "meta": "Stmt_Class",
        "namespace": []
    },
    "properties": [
        {
            "name": "name",
            "type": {
                "name": "string",
                "namespace": []
            },
            "modifier": {
                "private": true
            }
        }
    ],
    "methods":[]
}
EOJ;
    private string $subtag_expression = <<<EOJ
{
    "type": {
        "name": "SubTag",
        "meta": "Stmt_Class",
        "namespace": []
    },
    "properties": [],
    "methods":[],
    "extends": [
        {
            "name": "Tag",
            "meta": "Stmt_Class",
            "namespace": []
        }
    ]
}
EOJ;

    public function testInitialize(): void {
        $options = new Options([]);
        $entries = [
            new Entry('product', new PhpClassDummy('product', 'Product.php', $this->product_expression), $options),
            new Entry('product', new PhpClassDummy('product', 'Price.php', $this->price_expression), $options),
            new Entry('product', new PhpClassDummy('product', 'Name.php', $this->name_expression), $options),
        ];
        $rel = new Relation($entries, $options);

        $this->assertNotNull($rel, 'initialize Relation');
    }

    public function testGetRelations1(): void {
        $options = new Options([]);
        $entries = [
            new Entry('product', new PhpClassDummy('product', 'Product.php', $this->product_expression), $options),
            new Entry('product', new PhpClassDummy('product', 'Price.php', $this->price_expression), $options),
            new Entry('product', new PhpClassDummy('product', 'Name.php', $this->name_expression), $options),
        ];
        $rel = new Relation($entries, $options);
        $relations = $rel->getRelations();

        $this->assertSame(2, count($relations), 'count');
        $this->assertSame('  product.Product ..> product.Name', $relations[0], 'relation 1');
        $this->assertSame('  product.Product ..> product.Price', $relations[1], 'relation 2');
    }

    public function testGetRelations2(): void {
        $options = new Options([]);
        $entries = [
            new Entry('product', new PhpClassDummy('product', 'Product.php', $this->product_with_tags_expression), $options),
            new Entry('product', new PhpClassDummy('product', 'Price.php', $this->price_expression), $options),
            new Entry('product', new PhpClassDummy('product', 'Name.php', $this->name_expression), $options),
            new Entry('product', new PhpClassDummy('product', 'Tag.php', $this->tag_expression), $options),
        ];
        $rel = new Relation($entries, $options);
        $relations = $rel->getRelations();

        $this->assertSame(3, count($relations), 'count');
        $this->assertSame('  product.Product "1" ..> "*" product.Tag', $relations[0], 'relation 1');
        $this->assertSame('  product.Product ..> product.Name', $relations[1], 'relation 2');
        $this->assertSame('  product.Product ..> product.Price', $relations[2], 'relation 3');
    }

    public function testGetRelations_extends1(): void {
        $options = new Options([]);
        $entries = [
            new Entry('product', new PhpClassDummy('product', 'Tag.php', $this->tag_expression), $options),
            new Entry('product', new PhpClassDummy('product', 'SubTag.php', $this->subtag_expression), $options),
        ];
        $rel = new Relation($entries, $options);
        $relations = $rel->getRelations();

        $this->assertSame(1, count($relations), 'count');
        $this->assertSame('  product.Tag <|-- product.SubTag', $relations[0], 'relation 1');
    }
}
