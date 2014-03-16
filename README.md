# Basic App using the Decorator Design Pattern

Suppose your app is run on one code base for all your clients, and each client has paid for a set of plugins. 
Several plugins interact with eachother; for example, the project plugin may add a field to an invention object to denote which project the invention belongs to.

Furthermore, each client has specific customizations that cannot conflict with their plugin combination.

## The Decorator Design Pattern

A decorator design pattern is one which "wraps" functionality or attributes around a base object.
This is especially useuful for single-inheritance languages, such as PHP (hence the language choice of this project). 

## Simple Example

(A gist for this example is [available here](https://gist.github.com/thelowlypeon/9585326#file-basic-coffee-decorator-php).)

Consider a base object ```Coffee```. A Coffee has a cost and list of ingredients associated with it. The simplest coffee contains only "coffee".

Now let's create a decorator class. This will never be instatiated, so it's common to make it abstract, but not necessary. This example instead
constructs a decorator, and passes the decorator around. That way, we can keep the coffee instance untouched.

```php
public function __construct(Coffee $coffee) {
    $this->coffee = $coffee;
    $this->setCost();
    $this->setIngredients();
}
```

Note that the constructor takes an instance of ```Coffee```, and hangs onto it. From there, the decorator can add
to its coffee's cost or list of ingredients, or can add new methods or attributes. We can pass this decorator instance to _other_ decorators too.

So let's add a new decorator for adding milk, and its cost, to our coffee: 

```php
class MilkyCoffee extends CoffeeDecorator {
    private $decorator;
    protected $cost = 1;
    protected $ingredients = array('Milk');
 
    public function __construct(CoffeeDecorator $decorator) {
        $this->decorator = $decorator;
        $this->addMilk();
    }
 
    public function addMilk() {
        $this->decorator->cost = $this->decorator->getCost() + $this->cost;
        $this->decorator->ingredients = array_merge($this->ingredients, $this->decorator->getIngredients());
    }
}
```

When we construct a new ```MilkyCoffee```, we pass an instance of ```CoffeeDecorator``` to it. (We could alternately pass a class that implements
a known interface, as is done in the application.)

If we were to keep constructing ```MilkyCoffee``` using instances of ```MilkyCoffee```, we'd get _really_ milky coffee:

```php
$coffee = new Coffee(1.5); // price = $1.50, ingredients default to array('coffee')
$decorator = new CoffeeDecorator($coffee); // nothing happened
$milky = new MilkyCoffee($decorator); // hey hey, now the coffee is 2.50, and the ingredients are array('coffee', 'milk')
$very_milky = new MilkyCoffee($milky); // woah there, now the coffee is 3.50, and the ingredients are array('coffee', 'milk', 'milk'). yuck!
```

## My findings

Working in a very dynamic environment with classes that cannot be modified at runtime, utilizing the Decorator Design Pattern is an excellent option, 
given a few cavets:

* Things get real weird if you rely on ```get_class()``` or type hinting, because the decorator is not the same class (eg ```MilkyCoffee``` vs ```Coffee```).
  * You can get around this by utilizing interfaces and typehinting with the interface. But man, that can be even more planning!
* For real projects, this can become pretty time consuming up front, and requires lots of nitpicky details (namely, using ```$this->attr``` sometimes and ```$this->decorator->attr``` others).
* It requires a significant amount of planning. This is fine, and well worth it for big projects, but if your schema or business logic changes often, heed caution.

All in all, I'm astonished how flexible it is.
