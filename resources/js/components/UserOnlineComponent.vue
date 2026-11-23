<template>
    <div>
        <span class="spinner-border spinner-border-sm" v-if="loading"></span>
                <div class="container-fluid mb-3">
                    <div class="row mb-3">
                            <div class="col-12">
                                    <div class="alert alert-info">
                                            <h5 class="mb-0">
                                                    Всего онлайн:
<!--                                                {{ $total ?? collect($online)->sum('count') }}-->
                                                </h5>
                                        </div>
                                </div>
                        </div>

                    <div class="row">
                                <div class="col-md-6" v-for="item in users">
                                        <div class="card card-outline
                        {{ item.count > 0 ? 'card-success' : 'card-secondary' }}">

                                                <div class="card-header">
                                                        <h3 class="card-title">
                                                                {{ item.sertverIp }}
                                                            </h3>

                                                        <div class="card-tools">
                                                            <span class="badge text-primary
                                    {{ item.count > 0 ? 'badge-success' : 'badge-secondary' }}">
                                    {{ item.count }} online
                                </span>
                                                            </div>
                                                    </div>

                                                <div class="card-body p-2">

                                                            <ul class="list-group list-group-flush"
                                                                v-if="item.users && item.users.length">
                                                                <li class="list-group-item" v-for="user in item">
                                                                        <i class="fas fa-user text-primary mr-2"></i>
                                                                        {{ user }}
                                                                </li>
                                                            </ul>
                                                            <div class="text-center text-muted p-3" :else>
                                                                    Нет активных пользователей
                                                            </div>

                                                    </div>

                                            </div>
                                    </div>
                        </div>

                </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            loading:false,
            users:[],
        }
    },
    mounted() {
        this.loadServers();
    },
    methods:{
        async loadServers() {
            try {
                this.loading = true;
                const response = await axios.get('/admin/users/list');
                this.users = response.data;
                this.loading = false;
            } catch (error) {
                this.loading = false;
                console.error('Ошибка запроса:', error);
            }
        }
    }
}
</script>

