<?php

/**
 * https://www.cnblogs.com/zhouqi666/p/9103681.html
 * https://www.cnblogs.com/DeanChopper/p/4764512.html
 * 工厂模式实现
 *
 * @since  2019-06-22
 */
interface iShape {
    public function draw();
}

class Circle implements iShape {
    public function draw() {
        return "画一个圆形";
    }
}

class Square implements iShape {
    public function draw() {
        return "画一个正方形";
    }
}

class Rectangle implements iShape {
    public function draw() {
        return "画一个长方形";
    }
}

class MyFactory {

    const CIRCLE    = 'circle';
    const SQUARE    = 'square';
    const RECTANGLE = 'rectangle';

    private $shape;

    public function __construct($shape) {
        $this->shape = $shape;
    }

    public function factory() {
        switch ($this->shape) {
            case self::CIRCLE:
                return new Circle();
                break;
            case self::SQUARE:
                return new Square();
                break;
            case self::RECTANGLE:
                return new Rectangle();
                break;
            default:
                return null;
        }
    }
}


$shape  = (new MyFactory(MyFactory::CIRCLE))->factory();
$string = $shape->draw();
echo $string;
