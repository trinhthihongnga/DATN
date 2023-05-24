<template>
    <el-dialog v-model="dialogVisible" id="edit-category" class="bg-[#f4f1f8] max-h-[40%] overflow-scroll" width="500px" 
     style="margin-top: 108px !important;" :show-close="true">
        <template #header>
            <div class="text-center font-bold text-[18px]">
                Chỉnh sửa chủ đề
            </div>
        </template>
        <div class="mx-[4px] mt-[42px]">
            <div class="mt-[18px]">
                <div class="text-[16px] font-bold text-[#000]">Tên chủ đề <span class="text-[red]">*</span></div>
                <el-input class="mt-[2px]" v-model="dataForm.name" placeholder="Nhập tên hagtag" clearable/>
                <div v-if="errors.name" class="mt-[5px] text-[#ff0000]">
                    {{ errors.name[0] }}
                </div>
            </div>
        </div>
        <template #footer>
            <div class="pb-[12px] pt-[8px]">
                <div class="flex justify-end text-[14px] mr-[24px]">
                    <div @click="cancel"
                        class="cursor-pointer flex justify-center items-center w-[110px] rounded-[4px] bg-[#ffffff] py-[4px] h-[32px] text-[15px] text-black border border-[#7d7f92]">
                        Hủy bỏ
                    </div>
                    <div class="cursor-pointer flex justify-center items-center w-[110px] ml-[18px] rounded-[4px] bg-[#007bff] py-[4px] h-[32px] text-[15px] text-white"
                        @click="editHagtag">
                        Xác nhận
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
    components: {
        Link,
    },
    data() {
        return {
            dialogVisible: false,
            imageSelected: '',
            dataForm: {
                name: '',
            },
            errors: []
        }
    },
    methods: {
        open(row) {
            this.dialogVisible = true
            axios.get(route('admin.categories.show', row.id))
                .then(response => {
                    this.dataForm = response.data.data
                })
        },
        cancel() {
            this.dialogVisible = false;
        },
        editHagtag() {
            const pagram = { ...this.dataForm }
            axios.post(route('admin.categories.update', this.dataForm.id), pagram)
                .then(() => {
                    ElMessage({
                        showClose: true,
                        message: 'Cập nhật chủ đề thành công',
                        type: 'success',
                    })
                    this.dialogVisible = false
                    this.$emit('change-category')
                })
                .catch(errors => {
                    this.errors = errors.response.data.errors
                })
        }
    },
}
</script>
<style>
#edit-category .el-dialog__headerbtn {
    font-size: 24px !important;
}
#edit-category.el-dialog {
    border-radius: 4px !important;
    position: fixed !important;
    left: 0;
    right: 0;
}
#edit-category .el-dialog__header {
    position: fixed;
    min-width: 500px;
    background-color: #fff;
    border-bottom: 1px solid #ccc;
    margin-right: 0 !important;
    z-index: 100;
}
#edit-category .el-select,
#edit-category .el-date-editor {
    width: 100% !important;
}
#edit-category .el-input__inner {
    margin-top: 2px;
}
</style>

