const TodoList = {
    data() {
        return  {
            message: 'Hello Vue!',
            seen: true,
            InputText: '',
            groceryList: [
                {id: 0, text: 'Vegetables'},
                {id: 1, text: 'Cheese'},
                {id: 2, text: 'Whatever else humans are supposed to eat'}
            ],
            todos: [
                {text: 'Learn JavaScript'},
                {text: 'Learn Vue'},
                {text: 'Build something awesome'}
            ]
        };
    },
    methods: {
        reverseMessage: function () {
            this.message = this.message.split('').reverse().join('');
        }
    }
};

const app = Vue.createApp(TodoList);

app.component('todo-item', {
    props: ['todo'],
    template: `<li>{{ todo.text }}</li>`
});

app.mount('#learningVueApp');