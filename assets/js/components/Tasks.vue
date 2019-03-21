<template>
    <v-flex xs12>
        <v-layout row wrap>
            <v-flex xs12 md8>
                <template v-for="s in status">
                    <v-btn @click="filterByStatus(s.id)" :color="s.color" dark>{{ s.text }}</v-btn>
                </template>
            </v-flex>
            <v-flex xs12 md4>
                <v-combobox v-model="project" :items="projects" label="Projekt"></v-combobox>
            </v-flex>
        </v-layout>
        <v-card flat v-for="task in filteredTasks" :key="task.id">
            <v-layout row wrap :class="'mt-1 pa-3 task status-'+task.status">
                <v-flex xs12 md3>
                    <div class="caption grey--text">Naslov zadatka</div>
                    <div>{{ task.title }}</div>
                </v-flex>
                <v-flex xs12 md1>
                    <div><v-icon>{{ task.type.icon }}</v-icon></div>
                </v-flex>
                <v-flex xs12 md2>
                    <div class="caption grey--text">Rok</div>
                    <div v-if="task.dueDate">{{ formatDate(task.dueDate) }}</div>
                </v-flex>
                <v-flex xs12 md3>
                    <div class="caption grey--text">Projekt</div>
                    <div>{{ task.project.name }}</div>
                </v-flex>
                <v-flex xs12 md3>
                    <div class="caption grey--text">Dodijeljen</div>
                    <div>{{ task.assignee.name }}</div>
                </v-flex>
            </v-layout>
        </v-card>
        <div class="text-xs-right">
            <v-btn fab small dark color="teal" @click.stop="dialog.show = true"><v-icon dark>add</v-icon></v-btn>
        </div>
        <v-dialog v-model="dialog.show" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Novi zadatak</span>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12 sm6><v-combobox v-model="dialog.form.project" :items="projects" label="Projekt *"></v-combobox></v-flex>
                            <v-flex xs12 sm6><v-combobox v-model="dialog.form.type" :items="types" label="Vrsta *"></v-combobox></v-flex>
                            <v-flex xs12><v-text-field v-model="dialog.form.title" label="Naslov zadatka *" required></v-text-field></v-flex>
                            <v-flex xs12 sm6><v-combobox v-model="dialog.form.assignee" :items="users" label="Dodijeljen *"></v-combobox></v-flex>
                            <v-flex xs12 sm6>
                                <v-menu v-model="dialog.form.dueDateMenu" :close-on-content-click="false" :nudge-right="40" lazy transition="scale-transition" offset-y full-width max-width="290px" min-width="290px">
                                    <template v-slot:activator="{ on }">
                                        <v-text-field v-model="computedDueDateFormatted" label="Rok" hint="DD.MM.YYYY. format" persistent-hint prepend-icon="event" readonly v-on="on"></v-text-field>
                                    </template>
                                    <v-date-picker v-model="dialog.form.dueDate" no-title @input="dialog.form.dueDateMenu = false"></v-date-picker>
                                </v-menu>
                            </v-flex>
                            <v-flex xs12>
                                <v-textarea v-model="dialog.form.description" label="Opis zadatka"></v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="dialog.show = false">Zatvori</v-btn>
                    <v-btn color="blue darken-1" flat @click="saveTask" :loading="dialog.form.saveLoading">Spremi</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-flex>
</template>

<script>
    import moment from 'moment';
    import axios from 'axios';

    export default {
        data() {
            return {
                allTasks: null,
                filteredTasks: null,
                projects: [],
                types: [],
                users: [],
                project: null,
                status: [
                    {id: 0, color: '#aaaaaa', text: 'svi'},
                    {id: 1, color: '#000ddd', text: 'odraditi'},
                    {id: 2, color: '#d19f1a', text: 'u postupku'},
                    {id: 3, color: '#d17300', text: 'u pregledu'},
                    {id: 4, color: '#09a80d', text: 'završeno'},
                ],
                dialog: {
                    show: false,
                    form: {
                        project: null,
                        type: null,
                        title: null,
                        assignee: null,
                        dueDateMenu: false,
                        dueDate: null,
                        description: null,
                        saveLoading: false,
                    }
                }
            }
        },
        computed: {
            computedDueDateFormatted () {
                return this.formatDueDate(this.dialog.form.dueDate);
            }
        },
        props: {
            tasksJson: {
                type: String,
                required: true,
            },
            projectUrl: {
                type: String,
                required: true,
            },
            typeUrl: {
                type: String,
                required: true,
            },
            usersUrl: {
                type: String,
                required: true,
            },
            newTaskUrl: {
                type: String,
                required: true,
            }
        },
        methods: {
            formatDate(dateString) {
                if(dateString) {
                    let date = moment(dateString);
                    return date.format('DD.MM.YYYY.');
                } else {
                    return '';
                }
            },
            formatDueDate(date) {
                if (!date) return null;
                const [year, month, day] = date.split('-');
                return `${day}.${month}.${year}.`;
            },
            filterByStatus(status) {
                if(status !== 0) {
                    this.filteredTasks = this.allTasks.filter(el => {
                        if(this.project) return (el.status === status && el.project.id === this.project.value);
                        else return el.status === status;
                    });
                } else if(this.project) {
                    this.filteredTasks = this.allTasks.filter(el => {
                        return el.project.id === this.project.value;
                    });
                } else {
                    this.filteredTasks = this.allTasks;
                }
            },
            saveTask() {
                this.dialog.form.saveLoading = true;
                let bodyFormData = new FormData();
                bodyFormData.set('project', this.dialog.form.project.value);
                bodyFormData.set('type', this.dialog.form.type.value);
                bodyFormData.set('title', this.dialog.form.title);
                bodyFormData.set('assignee', this.dialog.form.assignee.value);
                if(this.dialog.form.dueDate) bodyFormData.set('dueDate', this.dialog.form.dueDate);
                if(this.dialog.form.description) bodyFormData.set('description', this.dialog.form.description);
                axios.post(this.newTaskUrl, bodyFormData)
                    .then(response => {
                        this.allTasks.push(response.data);
                        this.filteredTasks = this.allTasks;
                        this.dialog.form.saveLoading = false;
                        this.dialog.show = false;
                });
            },
        },
        created() {
            this.allTasks = JSON.parse(this.tasksJson);
            this.filteredTasks = this.allTasks;
            axios.get(this.projectUrl)
                .then(response => {
                    response.data.forEach(el => {
                        this.projects.push({'value': el.id, 'text': el.name});
                    });
            });
            axios.get(this.typeUrl)
                .then(response => {
                    response.data.forEach(el => {
                        this.types.push({'value': el.id, 'text': el.name});
                    });
            });
            axios.get(this.usersUrl)
                .then(response => {
                    response.data.forEach(el => {
                        this.users.push({'value': el.id, 'text': el.name});
                    });
            });
        }
    }
</script>

<style lang="scss" scoped>
    .task.status-1 {
        border-left: 4px solid #000ddd;
    }
    .task.status-2 {
        border-left: 4px solid #d19f1a;
    }
    .task.status-3 {
        border-left: 4px solid #d17300;
    }
    .task.status-4 {
        border-left: 4px solid #09a80d;
    }
</style>