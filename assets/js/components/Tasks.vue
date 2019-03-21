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
                    <div class="caption grey--text">Dodjeljen</div>
                    <div>{{ task.assignee.name }}</div>
                </v-flex>
            </v-layout>
        </v-card>
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
                project: null,
                status: [
                    {id: 0, color: '#aaaaaa', text: 'svi'},
                    {id: 1, color: '#000ddd', text: 'odraditi'},
                    {id: 2, color: '#d19f1a', text: 'u postupku'},
                    {id: 3, color: '#d17300', text: 'u pregledu'},
                    {id: 4, color: '#09a80d', text: 'završeno'},
                ],
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