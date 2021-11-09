# mermaid [![Build Status](https://travis-ci.org/knsv/mermaid.svg?branch=master)](https://travis-ci.org/knsv/mermaid)

[![Code Climate](https://codeclimate.com/github/knsv/mermaid/badges/gpa.svg)](https://codeclimate.com/github/knsv/mermaid)
[![Join the chat at https://gitter.im/knsv/mermaid](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/knsv/mermaid?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

Generation of diagrams and flowcharts from text in a similar manner as markdown.

Ever wanted to simplify documentation and avoid heavy tools like Visio when explaining your code?

This is why mermaid was born, a simple markdown-like script language for generating charts from text via javascript.

The code below would render the following image
<table>
    <tr><th>Code</th><th>Rendered diagram</th></tr>
    <tr>
        <td>
            <pre>
                <code>
graph TD;
    A-->B;
    A-->C;
    B-->D;
    C-->D;
                </code>
            </pre>
        </td>
        <td>
            <p align="center">
                <img src='http://www.sveido.com/mermaid/img/ex1.png' alt='Example 1'>
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <pre>
                <code>
sequenceDiagram
    participant Alice
    participant Bob
    Alice->>John: Hello John, how are you?
    loop Healthcheck
        John->>John: Fight against hypochondria
    end
    Note right of John: Rational thoughts &lt;br/>prevail...
    John-->>Alice: Great!
    John->>Bob: How about you?
    Bob-->>John: Jolly good!
                </code>
            </pre>
        </td>
        <td>
            <img src='http://www.sveido.com/mermaid/img/seq1.png' alt='Example 2'>
        </td>
    </tr>
    <tr>
        <td>
            <pre>
                <code>
classDiagram
    Class01 &lt;|-- AveryLongClass : Cool
    Class03 *-- Class04
    Class05 o-- Class06
    Class07 .. Class08
    Class09 --> C2 : Where am i?
    Class09 --* C3
    Class09 --|> Class07
    Class07 : equals()
    Class07 : Object[] elementData
    Class01 : size()
    Class01 : int chimp
    Class01 : int gorilla
    Class08 &lt;--> C2: Cool label
                </code>
            </pre>
        </td>
        <td>
            <img src='./docs/img/class-diagram.png' alt='Example 3'>
        </td>
    </tr>
    <tr>
        <td>
            <pre>
                <code>
gitGraph :
options
{
    "key": "value",
    "nodeWidth": 150,
    "nodeSpacing" : 150
}
end
    commit
    branch newbranch
    checkout newbranch
    commit
    commit
    checkout master
    commit
    commit
    merge newbranch
                </code>
            </pre>
        </td>
        <td>
            <img src='./docs/img/gitgraph.mm.png' alt='Example 4'>
        </td>
    </tr>

</table>


## Further reading

* [Usage](http://knsv.github.io/mermaid/#usage)
* [Flowchart syntax](http://knsv.github.io/mermaid/#flowcharts-basic-syntax)
* [Sequence diagram syntax](http://knsv.github.io/mermaid/#sequence-diagrams)
* [Mermaid client](http://knsv.github.io/mermaid/#mermaid-cli)
* [Demos](http://knsv.github.io/mermaid/#demos)


# Request for assistance

Things are piling up and I have hard time keeping up. To remedy this
it would be great if we could form a core team of developers to cooperate
with the future development mermaid.

As part of this team you would get write access to the repository and would
represent the project when answering questions and issues.

Together we could continue the work with things like:
* port the code to es6
* improved support for webpack
* modernizing the build
* adding more typers of diagrams like mindmaps, ert digrams etc
* improving existing diagrams

Don't hesitate to contact me if you want to get involved.


# For contributors

## Setup

Make sure you have Chrome browser installed, this project uses Chrome headless to running tests.

    yarn install


## Build

    yarn build

If you want real time incremental build:

    yarn watch


## Lint

    yarn lint

We use [JavaScript Standard Style](https://github.com/feross/standard).
We recommend you installing [editor plugins](https://github.com/feross/standard#are-there-text-editor-plugins) so you can get real time lint result.


## Test

    yarn test

Manual test in browser:

    open dist/demo/index.html

Manual test in Node.js:

    node dist/demo/index.js


## Distribtion

    yarn dist

Command above generates files into the `dist` folder, then you can publish them to npmjs.org.


# Credits

Many thanks to the [d3](http://d3js.org/) and [dagre-d3](https://github.com/cpettitt/dagre-d3) projects for providing the graphical layout and drawing libraries!

Thanks also to the [js-sequence-diagram](http://bramp.github.io/js-sequence-diagrams) project for usage of the grammar for the sequence diagrams. Thanks to Jessica Peter for inspiration and starting point for gantt rendering.

*Mermaid was created by Knut Sveidqvist for easier documentation.*

Knut has not done all work by him self, here is the full list of the projects [contributors](https://github.com/knsv/mermaid/graphs/contributors).
