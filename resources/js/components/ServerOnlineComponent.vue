<template>
    <div>
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Сервера</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <span class="spinner-border spinner-border-sm" v-if="loading"></span>
                    <table class="table table-bordered table-sm align-middle">
                        <thead>
                        <tr>
                            <th>NAME</th>
                            <th>IP</th>
                            <th>CPU</th>
                            <th>RAM</th>
                            <th>Disk</th>
                            <th>Net ↑↓</th>
                            <th>Xray</th>
                            <th>Uptime</th>
                            <th>link</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="server in servers">
                            <td>{{ server.serverName }}</td>
                            <td>{{ server.serverIp }}</td>
                            <td>{{ server.cpu }}</td>
                            <td>
                                <div class="progress" style="height: 15px;">
                                    <div class="progress-bar"
                                         :class="server.memoryPercentFormated > 80 ? 'bg-danger' : (server.memoryPercentFormated > 50 ? 'bg-warning' : 'bg-success')"
                                         :style="{width: server.memoryPercentFormated+'%'}">
                                        {{ server.memoryPercentFormated }} %
                                    </div>
                                </div>
                                <small>{{ server.memCurrentFormated }} </small>

                            </td>
                            <td>
                                <div class="progress" style="height: 15px;">
                                    <div class="progress-bar bg-warning"
                                         :style="{width:(server.diskPercentFormated || 0)+'%'}">
                                        {{ server.diskPercentFormated }} %
                                    </div>
                                </div>
                                <small>{{ server.diskCurrentFormated }}</small>
                                <!--                    {{server.diskCurrentFormated}} {{server.diskTotalFormated}}-->
                            </td>
                            <td>
                                ↑ {{ server.netIOUp }} ↓ {{ server.netIODown }}
                            </td>
                            <td>{{ server.xray }}</td>
                            <td>{{ server.uptime }}</td>
                            <td><a :href="server.serverLink" target="_blank">Перейти</a></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            loading: false,
            servers: [],
        }
    },
    mounted() {
        this.loadServers();
    },
    methods: {
        async loadServers() {
            try {
                this.loading = true;
                const response = await axios.get('/admin/servers/list');
                this.servers = response.data;
                this.loading = false;
            } catch (error) {
                this.loading = false;
                console.error('Ошибка запроса:', error);
            }
        }
    }
}
</script>

