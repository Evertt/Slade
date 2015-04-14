# Slade
A PHP templating engine inspired by Slim

Clone this repository, then run `composer dump-autoload` and you should be good to go.

The following nodes are included:

* Any line starting with a lower case letter, a dot, or a hash, is interpreted as an html tag and will thus be handled by the `TagNode`.
* `?` is for the `IfNode`, when you only want its children to be displayed if the provided variable returns a truthy.
* `!` is for the `UnlessNode`, which does pretty much the opposite of the `IfNode`.
* `>` is for the `ForeachNode`, which iterates over the elements of an iterable.
* `+` is for the `IncludeNode` and it includes another template into the current template.
* `=` is for the `VariableNode` and it will insert the value of a variable, encoded by htmlentities. To avoid encoding by htmlentities you can use `==`.
* `|` is for the `TextNode` and it will just print text, encoded with htmlentities.
* `<` is for the `HtmlNode` and it will print HTML as is, only with variables replaced by their values.
* `_` is for the `ExtendNode`. With this a view can extend a master view. **(Not yet implemented)**
* `-` is for the `YieldNode` and it should yield a predefined section. **(Not yet implemented)**
* `@` is for the `SectionNode`. It should assign its children to a section. **(Not yet implemented)**

Finally you can write `css:` and `javascript:` to insert css and javascript code.