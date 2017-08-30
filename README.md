# Fundic

[![Latest Stable Version](https://poser.pugx.org/marcosh/fundic/version)](https://packagist.org/packages/marcosh/fundic)
[![Build Status](https://travis-ci.org/marcosh/fundic.svg?branch=master)](https://travis-ci.org/marcosh/fundic)
[![Code Climate](https://codeclimate.com/github/marcosh/fundic/badges/gpa.svg)](https://codeclimate.com/github/marcosh/fundic)
[![Coverage Status](https://coveralls.io/repos/github/marcosh/fundic/badge.svg?branch=master)](https://coveralls.io/github/marcosh/fundic?branch=master)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/80cfd863dbd744e5af524c93f47967a4)](https://www.codacy.com/app/marcosh/fundic)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/marcosh/fundic/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/marcosh/fundic/?branch=master)

A purely functional Dependency Injection Container for PHP

## Install

Add `fundic` as a dependency to your project using [Composer](https://getcomposer.org) running

```bash
composer require marcosh/fundic
```

## Tests

Run the tests using

```bash
php vendor\bin\phpunit
```

## Theory

In its essence a dependency injection container is just a component which is able, from a key, to
retrieve a corresponding working object.

A common approach to do this is to configure how the corresponding object should be contructed or even
relay on autowiring based on class naming.

Another approach si to see a dependency injection container as a map that associates to a key a factory
which builds the object identified by the key, possibly using recursively the container itself.

`fundic` takes this idea to its core and, in fact, if you look at the essence, it is just a map
that associates keys to factories of the form

```php
interface ValueFactory
{
    /**
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, string $name);
}
```

## Basic usage

### Instantiate

Actually `fundic` gives you two containers:

- `Psr11Container` which implements the specifications of [PSR-11](https://github.com/container-interop/fig-standards/blob/master/proposed/container.md);
- `TypedContainer`, a more type safe version

You can create a new instance of them just by calling their static method `create`:

```php
$container = TypedContainer::create();
```

This will create an empty instance of the container that you can fill as you like.

### Configure

An empty container is not really useful. You could add new entries to the container using

```php
$container = $container->add($key, $factory);
```

where `$key` is a string and `$factory` is an instance of `ValueFactory`.

Be aware that `$container` is immutable and that `add` returns a new instance. Therefore it is
really important that you remember to assign its result to a variable.

### Retrieve

Both `Psr11Container` and `TypedContainer` implement
[`Psr\Container\ContainerInterface`](https://github.com/php-fig/container/blob/master/src/ContainerInterface.php)
(even if `TypedContainer` is just respecting the signature of the methods and not conforming to the annotations),
therefore you can query them using the `set` and `has` methods as follows

```php
// create a new empty container
$container = Psr11Container::create();

// instructs the container on how to build the object
// associated with the provided key
$container = $container->add('foo', $factory);

$container->has('foo'); // returns true

$object = $container->get('foo'); // retrieves the object associated to the key
```

## Container return values and exceptions

`Psr11Container` works as a standard container and conforms completely to the specifications of
[PSR-11](https://github.com/container-interop/fig-standards/blob/master/proposed/container.md).
Hence, the result of any call to the `get` method is the expected object.

`Psr11Container::get` also throws exceptions if a key is not found or if there is an error while
building the return value.

On the other hand, `TypedContainer` alone does not totally conform to the specifications of
[PSR-11](https://github.com/container-interop/fig-standards/blob/master/proposed/container.md),
specifically in the return values of `get` and the handling of the exceptions.

In order to make the code purely functional and to avoid unwanted side effects, the result
of `get` is not directly the desired object, but a `Result` data structure which could
have the following values:

- `Just`, which is a wrapper around the desired value that can be retrieved using `Just::get`;
- `NotFound`, which represents the fact that such an entry is not present in the container;
- `Exception`, which represents the fact that something wrong happened while invoking the factory;

These above are just values and you could do whatever you want with them (immediately react to them,
pass them around, etc ...)

## Factories

Some factories are provided to ease the creation of `Fundic\Factory\ValueFactory` instances.

It goes without saying that you could provide your own ad-hoc implementations of `Fundic\Factory\ValueFactory`.

### ConstantFactory

If you need to store in the container a constant value, may it be a native data type, an array
or an object, you could use the `Fundic\Factory\ConstantFactory` as follows:

```php
$value = // your constant that needs to be stored in the container

$container->add('foo', new ConstantFactory($value));

$container->get('foo');
```

The `ConstanctFactory` class wraps the value in a `Fundic\Factory\ValueFactory` which always returns
the provided value.

### ClassNameFactory

If you need to retrieve from the container an object with no (or only optional) dependencies, you could
use a `Fundic\Factory\ClassNameFactory`, passing to it just the class name, as follows:

```php
class Foo { ... } // no non optional dependencies in the constructor

$container->add(Foo::class, new ClassNameFactory(Foo::class));

$container->get(Foo::class);
```

The `ClassNameFactory` just calls `new` on the provided class name and returns a new instance of the class.

### Callable factory

The most generic `Fundic\Factory\ValueFactory` implementation that we provide is `Fundic\Factory\CallableFactory`,
which just wraps any callable with the same signature of `ValueFactory` (i.e. it needs to have as input
parameters a `Psr\Container\ContainerInterface` and a `string` which is the class name). For example:

```php
$callable = function (ContainerInterface $container, string $name) { ... };

$container->add(Foo::class, new CallableFactory($callable));

$container->get(Foo::class);
```

## Factory decorators

Sometimes you want to modify how a specific key is built and retrieved from the container without touching
the provided factory.

An easy mechanism to allow this possibility is to use the decorator pattern. This means that we wrap
our factory with another factory which receives the first factory as a constructor argument.
In functional terms, suppose we have a factory `f` for a specific `foo` key
(i.e. `f : (ContainerInterface, string) -> foo`); what we do is passing the whole `f` to `g` where
`g(f) : (ContainerInterface, string) -> foo`.

This allows us to modify the result of the inner factory before returning it, or even avoiding to call
the inner factory and return a newly built value.

You could provide your own factory decorators to create complex workflows for object creations. Decorators
are highly composable, so you could use several of them to build a single object.

Some decorators of common use are provided by the library.

### Memoize

If you want to retrieve the same instance of an object every time you ask a particular key to the container,
you need to store the result obtained the first time somewhere and the return that instead of creating
a new instance every time.

This is exactly what the `Memoize` decorator does. The first time it calls the inner factory to build the object,
and then always returns that particular instance.

```php
class Foo { ... }

$container->add(Foo::class, new Memoize(new ClassNameFactory(Foo::class)));

$container->get(Foo::class); // a new instance of Foo is built and returned
$container->get(Foo::class); // the same instance of Foo is returned
```

### Proxy

If the building process of a object is particularly heavy, you could desire to postpone it until the very last
moment when you are sure you need an instance of that particular object.

To do this you could proxy your object and initially return a wrapper that will build the actual object only
once a method is called on it.

You could do this using the `Proxy` decorator, as follows:

```php
class Foo { ... } // class which is heavy to build

$container->add(Foo::class, new Proxy(new ClassNameFactory(Foo::class)));

$foo = $container->get(Foo::class); // returns a proxy

$foo->bar(); // here we really instantiates Foo and call the bar method on it
```
