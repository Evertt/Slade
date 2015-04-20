# Slade
A PHP templating engine inspired by Slim

## Install

Use

    composer require evertt/slade

to include this package into your Laravel project. Note, this package requires PHP 5.5 and Laravel 5. Then in `config/app.php` add `'Slade\SladeServiceProvider'` to your list of service providers.

## Usage

To use this engine in your controller, simply put

    use Slade\Slade;

at the top of your controller file and then in any action use:

    return Slade::parse('users.index', compact('user'));

Slade assumes that all slade view files end in `.slade`. So `users.index` points to the view file `resources/views/users/index.slade`.

## Example

The following template

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
            
Could parse into the following HTML:

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
    
You can also extend another template this way:

    _ layouts.default
      @ content
        p This paragraph will be assigned to the 'content' section
        
Which will then extend `resources/views/layouts/default.slade` and the paragraph will appear wherever the following line is included in `layouts/default.slade`:

    - content
          
## Nodes

The following nodes are included:

* Any line starting with a lower case letter, a dot, or a hash, is interpreted as an html tag and will thus be handled by the `TagNode`.
* `?` is for the `IfNode`, when you only want its children to be displayed if the provided variable returns a truthy.
* `!` is for the `UnlessNode`, which does pretty much the opposite of the `IfNode`.
* `>` is for the `ForeachNode`, which iterates over the elements of an iterable. It automatically names the individual items the singular name of the original variable. So if you iterate over the variable called `people`, the individual items will be called `person`. If it does not know the singular version of a variable name, then it just uses the same variable name again.
* `+` is for the `IncludeNode` and it includes another template into the current template.
* `=` is for the `VariableNode` and it will insert the value of a variable, encoded by htmlentities. To avoid encoding by htmlentities you can use `==`.
* `|` is for the `TextNode` and it will just print text, encoded with htmlentities.
* `<` is for the `HtmlNode` and it will print HTML as is, only with variables replaced by their values.
* `_` is for the `ExtendNode`. With this a view can extend a master view.
* `-` is for the `YieldNode` and it yields a predefined section.
* `@` is for the `SectionNode`. It assigns its children to a section.

Finally you can write `css:` and `javascript:` to insert css and javascript code.