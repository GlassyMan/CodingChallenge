Task:

Build a simple template engine in a language of your choice (extra credit for PHP),
that takes template.tmpl (no touching) and the following variables (format them for your chosen language) as input.

- Aufgabe:

Erstellen Sie eine einfache Template Engine in einer Sprache Ihrer Wahl (extra Plus für PHP),
das nimmt template.tmpl (keine Änderung) und die folgenden Variablen (formatieren sie für die gewühlte Sprache) als Eingabe.

- Variables:

Name  = "Your name goes here"
Stuff = [
  [
    Thing = "roses",
    Desc  = "red"
  ],
  [
    Thing = "violets",
    Desc  = "blue"
  ],
  [
    Thing = "you",
    Desc  = "able to solve this"
  ],
  [
    Thing = "we",
    Desc  = "interested in you"
  ]
]

- More extra credit:

Use (and handle) extra.tmpl instead of template.tmpl

- Mehr extra Guthaben:

Verwenden Sie (und behandeln Sie) extra.tmpl statt template.tmpl

---

- template.tmpl

Hey {{Name}}, here's a poem for you:

{{#each Stuff}}
  {{Thing}} are {{Desc}}
{{/each}}

---

- extra.tmpl

Hey {{Name}}, here's a slightly better formatted poem for you:

{{#each Stuff}}
  {{Thing}} are {{Desc}}{{#unless @last}},{{else}}!{{/unless}}
{{/each}}
