# Slade
A PHP templating engine inspired by both Ruby Slim and Laravel Blade

# Disclaimer

I want to let you know that I don't believe this code is production ready yet. It's missing features to make it really powerful and although I've written tests I'm sure there are probably still important bugs I've missed. What I would really like is if you'd like to test it, look at the code and send me suggestions and pull-requests to make it better.

## Install

Use

    composer require evertt/slade

to include this package into your project. Note, this package requires PHP 5.5. If you want to use it with Laravel, then in `config/app.php` add `'Slade\SladeServiceProvider'` to your list of service providers. If you want to use this package independently from Laravel, then just make sure you set `Slade::$templatePaths` to an array of the paths to the root folder of your templates.

So if you set:

```php
Slade::$templatePaths = ['/my-project/views', '/my-project/some-vendor/views'];
```

And you ask Slade to parse the view `users.index` then it will first look for the file `/my-project/views/users/index.slade` and if that doesn't exist it will look for `/my-project/some-vendor/views/users/index.slade`.

## Usage

To use this engine in your controller for example, simply put

```php
use Slade\Slade;
```

at the top of your controller file and then in any action use:

```php
return Slade::parse('users.index', compact('user'));
```


## Example

The following template

```slim
doctype html
html
  head
    title Slade
    link href="style.css"
    
  body
    h1 My first Slade template!
    
    ? name
      p
        | Hello {{name}}, this line only appears
        | if the name variable contains a truthy.
    
    ! name
      p There is no name.
      
    h2 Here is a list of names of people:
    ul
      > people
        li = person.name
```
            
Could parse into the following HTML:

```html
<!DOCTYPE html>
<html>
  <head>
    <title>Slade</title>
    <link href="style.css">
  </head>
  
  <body>
    <h1>My first Slade template!</h1>
    
    <p>
      Hello John Doe, this line only appears
      if the name variable contains a truthy.
    </p>
    
    <h2>Here is a list of names of people:</h2>
    <ul>
      <li>Harry</li>
      <li>Ron</li>
      <li>Hermione</li>
    </ul>
  </body>
</html>
```
    
You can also extend another template this way:

```slim
_ layouts.default
  @ content
    p This paragraph will be assigned to the 'content' section
```

Which will then extend for example `layouts/default.slade` and the paragraph will appear wherever the following line is included in `layouts/default.slade`:

```slim
- content
```

## Nodes

The following nodes are included:

* Any line starting with a lower case letter, a dot, or a hash, is interpreted as an html tag and will thus be handled by the `TagNode`.
* `?` and `!` are for the `ConditionalNode`, when you want its children to be displayed based on wether a conditional statement returns a truthy (or a falsy in case of the `!`).
* `>` is for the `ForeachNode`, which iterates over the elements of an iterable. It automatically names the individual items the singular name of the original variable. So if you iterate over the variable called `people`, the individual items will be called `person`. If it does not know the singular version of a variable name, then it just uses the same variable name again.
* `+` is for the `IncludeNode` and it includes another template into the current template.
* `=` is for the `VariableNode` and it will insert the value of a variable, encoded by htmlentities. To avoid encoding by htmlentities you can use `==`.
* `|` is for the `TextNode` and it will just print text, encoded with htmlentities.
* `<` is for the `HtmlNode` and it will print HTML as is, only with variables replaced by their values.
* `_` is for the `ExtendNode`. With this a view can extend a master view.
* `-` is for the `YieldNode` and it yields a predefined section.
* `@` is for the `SectionNode`. It assigns its children to a section.

Finally you can write `css:` and `javascript:` to insert css and javascript code.
