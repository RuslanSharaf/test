<?php
class Animal {
    function makeSound()
    {
        print "Ошибка: Этот метот должен быть перекрыт в дочернем классе";
    
    }
}
class Cat extends Animal {
    function makeSound() 
    {
        print "мяу";
                
    }
}
class Dog extends Animal {
    function makeSound() 
    {
        print "гав";
        
    }
function printTheRightSound($obj) {
{
    if ($obj instanceof Animal) {
        $obj->makeSound();
    } else {
        print "Ошибка: Неопознанный объект";
    }
    print "\n";
}
printTheRightSound(new Cat());
printTheRightSound(new Dog());
}
    
}
?>
