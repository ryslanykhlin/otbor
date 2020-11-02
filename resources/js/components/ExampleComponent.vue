<template>
    <div>
        <template v-if="errorLogin === null || errorLogin === true">
            <div class="form__container">
                <div class="form">
                    <h1 class="form__title">Вход</h1>

                    <template v-if="!isAdminAuth">
                        <div v-if="errorAddPass !== null" class="error">
                            <div v-for="error in errorAddPass">{{error[0]}}</div>
                        </div>
                        <div class="form__group">
                            <input type="text" class="form__input" placeholder=" " v-model="FIO">
                            <label class="form__label">ФИО</label>
                        </div>

                        <div class="form__group">
                            <input type="text" class="form__input" placeholder=" " v-model="email">
                            <label class="form__label">Email</label>
                        </div>
                        <div class="form__group">
                            <input type="radio" name="typepass" @click="isTypeTime = false" checked>
                            <label>Постоянный</label>
                            <input type="radio" name="typepass" @click="isTypeTime = true">
                            <label>Временный</label>
                        </div>
                        <template v-if="isTypeTime">
                            <div class="form__group">
                                <input type="date" class="form__input" placeholder=" " v-model="dataStart">
                                <label class="form__label">Дата начала</label>
                            </div>

                            <div class="form__group">
                                <input type="date" class="form__input" placeholder=" " v-model="dataEnd">
                                <label class="form__label">Дата окончани</label>
                            </div>

                            <div class="form__group">
                                <textarea type="text" class="form__input" placeholder=" " v-model="purpose"></textarea>
                                <label class="form__label">Цель посещени</label>
                            </div>
                        </template>
                        <button class="form__button" @click="addPass()">Вход</button>
                        <div class="link" @click="isAdminAuth = true">для администратора/оператора</div>
                    </template>
                    <template v-if="isAdminAuth">
                        <template v-if="errorLogin">
                            <div class="error">Неправильный логин или пароль</div>
                        </template>
                        <div class="form__group">
                            <input type="text" class="form__input" placeholder=" " v-model="login">
                            <label class="form__label">Login</label>
                        </div>
                        <div class="form__group">
                            <input type="password" class="form__input" placeholder=" " v-model="password">
                            <label class="form__label">Password</label>
                        </div>
                        <button class="form__button" @click="sugnin()">Вход</button>
                        <div class="link" @click="isAdminAuth = false">для посетителей</div>
                    </template>
                </div>
            </div>
        </template>
        <template v-if="errorLogin === false">
            <template v-if="account_status === 'admin'">
                <div class="header">
                    <div class="header__title">Панель Администратора</div>
                </div>
                <div class="body">
                    <table>
                        <table border="1">
                            <caption>Таблица размеров обуви</caption>
                            <tr>
                                <th>ФИО</th>
                                <th>email</th>
                                <th>Европа</th>
                                <th>Длина ступни, см</th>
                            </tr>
                            <tr v-for="pass in allPass"><td>{{pass.FIO}}</td><td>{{pass.email}}</td><td>36</td><td>23</td></tr>
                        </table>
                    </table>
                </div>
            </template>
            <template v-if="account_status === 'operator'">
                <div class="header">
                    <div class="header__title">Панель Оператора</div>
                </div>
            </template>
        </template>
    </div>
</template>

<script>
    export default {
        data:() => ({
            isTypeTime: false,
            isAdminAuth: false,
            FIO:'',
            email:'',
            dataStart:'',
            dataEnd:'',
            purpose:'',
            login:'',
            password:'',
            errorAddPass:null,
            errorLogin:null,
            account_status: null,
            allPass: null
        }),
        methods:{
            sugnin(){
                axios.post('api/login',{
                    name:this.login,
                    password:this.password
                }).then(res => {
                    if (res.data.status === 'error'){
                        this.errorLogin = true
                        this.account_status = res.data.account_status
                    }
                    if (res.data.status === 'success'){
                        this.errorLogin = false
                    }
                    this.account_status = res.data.account_status
                })
            },
            addPass(){
                axios.post('/api/addPass',{
                    FIO: this.FIO,
                    email: this.email,
                    dataStart: this.dataStart,
                    dataEnd: this.dataEnd,
                    purpose: this.purpose
                }).then(res => {
                    if (res.data.status === 'error'){
                        this.errorAddPass = res.data.errors
                    }
                });
            },
        },
        mounted() {
            axios.get('api/getAllPass').then(res => {
                this.allPass = res.data.allPass
            })
        }
    }
</script>
