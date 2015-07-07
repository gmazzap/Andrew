# Andrew

> Proxy objects to access (dynamic and static) private properties and methods.

-------

[![travis-ci status](https://img.shields.io/travis/Giuseppe-Mazzapica/Andrew.svg?style=flat-square)](https://travis-ci.org/Giuseppe-Mazzapica/Andrew)
[![codecov.io](https://img.shields.io/codecov/c/github/Giuseppe-Mazzapica/Andrew.svg?style=flat-square)](http://codecov.io/github/Giuseppe-Mazzapica/Andrew?branch=master)
[![license](https://img.shields.io/github/license/Giuseppe-Mazzapica/Andrew.svg?style=flat-square)](http://opensource.org/licenses/MIT)

-------

# What

Andrew allow to access (read & write) private properties and methods of any objects.

It provides 2 "proxy" objects, one for dynamic properties and methods, the other for static properties and method.


# Proxy

Let's assume following class

```php
class Foo
{
  private $var = 'I am private';
  
  private function testMe() {
    return 'I am private';
  }
}
```

With *Andrew* is possible to:

```php
$proxy = new Andrew\Proxy(new Foo());

// getter
echo $proxy->var; // 'I am private'

// setter
$proxy->var = 'I WAS private';
echo $proxy->var; // 'I WAS private'

// isser
isset($proxy->var); // true

// unsetter
unset($proxy->var);
isset($proxy->var); // false

// caller
echo $proxy->testMe() // 'I am private'
```


# Static Proxy

Let's assume following class

```php
class Foo
{
  private static $var = 'I am private static';
  
  private static function testMe() {
    return 'I am private static';
  }
}
```

With *Andrew* is possible to:

```php
$proxy = new Andrew\StaticProxy('Foo'); // we pass class name, not object

// getter
echo $proxy->var; // 'I am private static'

// setter
$proxy->var = 'I WAS private static';
echo $proxy->var; // 'I WAS private static'

// isser
isset($proxy->var); // true

// caller
echo $proxy->testMe() // 'I am private static'
```

Note that `StaticProxy` has **not** unsetter, because PHP [does not allow unsett static variables](http://3v4l.org/91GTk).

If you try to unset anything on a `StaticProxy` object, *Andrew* will throw an Exception.

# Exceptions

There are several exceptions thrown by *Andrew*. All of them are in the namespace `Andrew\Exception`.

They are:

 - `ArgumentException` thrown when an invalid type of argument is used. E.g. when a method expects a string and receives a number or anything else.
 - `ClassException` thrown when a method expects a class but receives a string that is not a valid class name.
 - `PropertyException` when trying to access a non-existent variable, or either when trying to access a static variable using `Proxy`or a non-static property using `StaticProxy`.
 - `MethodException` when trying to access a non-existent method, or either when trying to access a static method using `Proxy`or a non-static method using `StaticProxy`.
 - `RuntimeException` when trying to unset a static variable


# Installation

Install via Composer, package name is `"gmazzap/andrew"` and it is available on [Packagist](https://packagist.org/packages/gmazzap/andrew).


# Should I use this in production?

No. But may be quite useful in unit tests.
 

# Requirements

*Andrew* has no dependencies. Requires PHP 5.4+, works with PHP 7 and HHVM. 

# License

MIT

