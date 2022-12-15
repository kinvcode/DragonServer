<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <!-- import CSS -->
    <link rel="stylesheet" href="/static/css/iconfont.css">
    <link rel="stylesheet" href="/static/css/index.css">
    <link rel="stylesheet" href="/static/css/home.css">
</head>
<body>
<div id="app">
    <el-card>
        <el-row>
            <el-col :span="6" :offset="6">
                <el-form ref="form" :model="form" label-width="80px">
                    <el-form-item label="策略名称">
                        <el-input v-model="form.name"></el-input>
                    </el-form-item>
                    <el-form-item label="任务类型">
                        <el-select v-model="form.region" placeholder="请选择任务类型" @change="jobChangeHandle">
                            <el-option :label="type.name" :value="type.id" v-for="type in job_types"></el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
            </el-col>
        </el-row>
    </el-card>
</div>
</body>
<!-- import Vue before Element -->
<script src="/static/js/vue.js"></script>
<!-- import JavaScript -->
<script src="/static/js/index.js"></script>
<script>
    Dcat.ready(function () {
        var app = new Vue({
            el: '#app',
            data: function () {
                return {
                    job_types: {!! $job_types !!},
                    form: {
                        name: '',
                        region: '',
                    }
                }
            },
            methods:{
                jobChangeHandle(value)
                {
                    console.log(value);
                },
            },
        })
    })
</script>
</html>
