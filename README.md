# Slade
A PHP templating engine inspired by both Ruby Slim and Laravel Blade

# Disclaimer

I want to let you know that I don't believe this code is production ready yet. It's missing features to make it really powerful and although I've written tests I'm sure there are probably still important bugs I've missed. What I would really like is if you'd like to test it, look at the code and send me suggestions and pull-requests to make it better.

## Install

Use

    composer require evertt/slade

to include this package into your Laravel project. Then in `config/app.php` add `Slade\ServiceProvider::class` to your list of service providers.

## Usage

To use this engine all you need to do is create template files that end in `.slade.php` instead of `.blade.php`.


## Example

The following template

```
doctype html
html
  head
    title Slade
    link href="style.css"
    
    css:
      body {
        color: #333;
      }
    
  body
    h1 My first Slade template!
    
    ? $name
      p
        | Hello $name, this line only appears
        | if the name variable contains a truthy.
    
    ! $name
      p There is no name.
      
    div
      <p>
        It also works fine with just plain html.
      </p>
      
    h2 Here is a list of names of people:
    ul
      > $people
        li = $person->name
        
    + elements.footer
```
            
Could parse into the following HTML:

```html
<!DOCTYPE html>
<html>
  <head>
    <title>Slade</title>
    <link href="style.css">
    
    <style>
      body {
        color: #333;
      }
    </style>
  </head>
  
  <body>
    <h1>My first Slade template!</h1>
    
    <p>
      Hello John Doe, this line only appears
      if the name variable contains a truthy.
    </p>
    
    <div>
      <p>
        It also works fine with just plain html.
      </p>
    </div>
    
    <h2>Here is a list of names of people:</h2>
    <ul>
      <li>Harry</li>
      <li>Ron</li>
      <li>Hermione</li>
    </ul>
    
    <footer>
      &copy; Me 2015
    </footer>
  </body>
</html>
```
    
You can also extend another template this way:

```
_ layouts.default
  @ content
    p This paragraph will be assigned to the 'content' section
```

Which will then extend for example `layouts/default.slade.php` and the paragraph will appear wherever the following line is included in `layouts/default.slade.php`:

```
- content
```

## Inserting variables

As you saw, you can insert variables in a few ways. I want to show a few more.

```
p
  | So this is a block of text in which you can put variables.
    You can do that in the following manner:
    $var or {$var} or ${var}. The {} syntax only works if
    there's no whitespace after the { and before the } though.
    And finally you can also execute function calls like so:
    {implode(' ', $var)}. Again, make sure there's no whitespace
    immediately following the { or immediately preceding the }.
```

## More

There's a lot more you can do. I intend to write a more complete wiki about it soon. If you'd like to contribute to the docs or the code or suggest any features then please file an issue or submit a pull-request. That would be the greatest gift for me.