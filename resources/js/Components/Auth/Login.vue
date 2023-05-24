<template>
    <el-dialog v-model="dialogVisible" width="580px" class="bg-[#f4f1f8]" :show-close="false">
        <template #header>
            <div class="text-center font-bold text-[18px] pb-[6px] text-[#9f224e]">
                {{ title }}
            </div>
        </template>
        <div class="border-[1px] py-[12px]">
            <el-form class="main-input px-5 pt-2" @keyup.enter.prevent="login">
                <div class="flex items-center">
                    <div class="w-[150px] font-bold text-[#000]">Email đăng nhập</div>
                    <div class="flex-auto">
                        <el-input v-model="user.email" placeholder="Nhập email hoặc tên đăng nhập" clearable/>
                    </div>
                </div>
                <div v-if="errors.email" class="mt-[5px] ml-[148px] text-[#ff0000]">
                    {{ errors.email }}
                </div>
                <div class="flex items-center mt-3">
                    <div class="w-[150px] font-bold text-[#000]">Mật khẩu</div>
                    <div class="flex-auto">
                        <el-input v-model="user.password" placeholder="Nhập mật khẩu" show-password/>
                    </div>
                </div>
                <div v-if="errors.password" class="mt-[5px] ml-[148px] text-[#ff0000]">
                    {{ errors.password }}
                </div>
            </el-form>
        </div>
        <template #footer>
            <div>
                <div class="pb-[12px]">
                    <div class="flex justify-center items-center">
                        <div
                            class="cursor-pointer flex justify-center items-center gap-1 w-[120px] mr-[18px] bg-[#9f224e] py-[4px] h-[32px] text-[15px] text-white"
                            @click="login">
                            Đăng nhập
                        </div>
                        <div @click="close"
                            class="cursor-pointer flex justify-center items-center gap-1 w-[120px] bg-[#ffffff] py-[4px] h-[32px] text-[15px] text-black border border-[#7d7f92]">
                            Thoát
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </el-dialog>
</template>

<script>
import axios from 'axios';
import { Link } from '@inertiajs/vue3'
import { ElMessage } from 'element-plus'

export default {
    components:{
        Link,
    },
    data() {
        return {
            dialogVisible: false,
            title: 'Đăng nhập',
            currentTab: 'login',
            user: {
                email: "",
                password: "",
            },
            forgotUser: {
                email: "",
                otp: "",
                password: "",
                confirm_password: "",
            },
            errors: [],
        }
    },
    watch: {
        dialogVisible(value) {
            if(value == false) {
                this.clearResult()
            }
        }
    },
    methods: {
        clearResult() {
            this.user.email = ""
            this.user.password = ""
            this.forgotUser.email = ""
            this.forgotUser.otp = ""
            this.forgotUser.password = ""
            this.forgotUser.confirm_password = ""
            this.errors = [];
        },
        open() {
            this.email = ""
            this.password = ""
            this.title = 'Đăng nhập'
            this.currentTab = 'login'
            this.dialogVisible = true
        },
        close() {
            this.dialogVisible = false
        },
        validateUser() {
            let validate = true;
            if (!(this.user.email && this.user.email.trim())) {
                validate = false
                this.errors.email = "Vui lòng nhập email"
            } else if (!this.validateEmail(this.user.email)) {
                validate = false
                this.errors.email = "Email không đúng định dạng"
            }
            if (!(this.user.password && this.user.password.trim())) {
                validate = false
                this.errors.password = "Vui lòng nhập mật khẩu"
            }
            return validate
        },
        validateEmail(email) {
            let validEmail = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            if (email.match(validEmail)) {
                return true
            } else {
                return false
            }
        },
        async login() {
            if(this.validateUser()) {
                const pagram = { ...this.user }
                await axios.post(route('guest.login'), pagram)
                    .then(response => {
                        ElMessage({
                            showClose: true,
                            message: 'Đăng nhập tài khoản thành công',
                            type: 'success',
                        })
                        this.dialogVisible = false
                        this.$emit('login')
                    })  
                    .catch(errors => {
                        console.log(errors)
                        this.errors = errors.response.data.errors
                    })
            }
        }
    },
}
</script>
<style>
.el-dialog {
    border-radius: 4px !important;
}
.el-dialog__header {
    border-bottom: 1px solid #ccc;
    margin-right: 0 !important;
}
.main-input .el-input__wrapper {
    box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.5);
}
</style>

