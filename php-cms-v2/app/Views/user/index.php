<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Introduction — Vue.js</title>
        <meta charset="utf-8">
        <meta name="description" content="Vue.js - The Progressive JavaScript Framework">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        
    </head>

    <body>
        <h1>Hello</h1>

        <div id="app">
            {{message}}
            <input v-model="message" />
        </div>

        <div id="app2">
            <span v-bind:title="message">
                Hover your mouse over me for a few seconds
                to see my dynamically bound title!
            </span>

            <div v-if="seen">Hello-1</div>
            <ul>
                <li v-for="row in list">({{row.id}}) : {{row.name}}</li>
            </ul>

            <ol>
                <todo-item v-for="row in list" v-bind:todo="row" v-bind:key="row.id"></todo-item>
            </ol>
            
            <button v-on:click="reverseMessage">Click</button>
        </div>


        <script>

            Vue.component('todo-item', {
                props: ['todo'],
                template: '<li>This is todo {{todo.name}} </li>'
            });


            var app2 = new Vue({
                el: '#app2',
                data: {
                    count: 10,
                    message: 'Hello my friend',
                    seen: true,
                    list: [
                        {id: 1, name:'Name-1'},
                        {id: 2, name:'Name-2'},
                        {id: 3, name:'Name-3'},
                    ]
                },
                methods: {
                    reverseMessage: function () {
                        this.seen = !this.seen;
                        let c = this.count++;
                        this.list.push({id: c, name:'Name-' + c})
                    }
                },

                created: function () {
                    console.log('App2 Created : ', this.list)
                },
            });

            app2.list.push({id: 4, name:'Name-4'})
            app2.list.push({id: 5, name:'Name-5'})

            var app = new Vue({
                el: '#app',
                data: {
                    message: 'Hello Hello'
                }
            });
        </script>
    </body>
</html>
