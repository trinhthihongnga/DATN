<template>
    <el-dialog v-model="dialogVisible" id="show-post" class="bg-[#f4f1f8] h-[90%] overflow-scroll" width="800px" 
     style="margin-top: 34px !important;" :show-close="true">
        <template #header>
            <div class="text-center font-bold text-[18px] text-[#9f224e]">
                Xem bài viết
            </div>
        </template>
        <div class="flex mt-[38px]">
            <div class="py-[12px] px-[12px] w-[100%]">
                <div class="text-[22px] font-bold">
                    {{ dataForm.title }}
                </div>
                <div class="flex items-center my-[12px]">
                    <div class="text-[16px] text-[#076db6] font-bold">
                        {{ dataForm.categoryName }}
                    </div>
                    <div class="mx-[24px] text-[16px] font-bold text-[#333]">
                        {{ dataForm.creator }}
                    </div>
                    <div class="text-[14px]">
                        {{ dataForm.created_at }}
                    </div>
                    <div class="ml-[24px] text-[18px] flex items-center" v-if="dataForm.is_approved">
                        <i class="bi bi-eye-fill"></i>
                        <span class="ml-[4px] text-[15px] font-bold">{{ count_view }}</span>
                    </div>
                </div>
                <div class="mt-[18px]">
                    <el-image
                        style="width: 100%; height: 100%;"
                        :src="dataForm.image"
                        :zoom-rate="1.2"
                        preview-teleported="true"
                        hide-on-click-modal="true"
                        :preview-src-list="[dataForm.image]"
                        :initial-index="1"
                        fit="cover"
                    />
                    <div class="text-[14px] text-center mt-[8px]">
                        {{ dataForm.description }}
                    </div>
                </div>
                <div v-html="dataForm.content" class="mt-[12px] text-[16px] mb-[18px]"></div>
            </div>
        </div>
        <template #footer v-if="!dataForm.is_approved">
            <div class="flex justify-center pb-[32px]">
                <div class="w-[120px] h-[38px] text-center leading-[38px] rounded-[4px] bg-[#9f224e] text-white
                 text-[16px] cursor-pointer active:scale-[0.95]" @click="approvePost(dataForm.id)">
                    Xác nhận
                </div>
            </div>
        </template>
        <template #footer v-else>
            <div class="flex justify-center pb-[32px]">
                <div class="w-[120px] h-[38px] text-center leading-[38px] rounded-[4px] bg-[#9f224e] text-white
                 text-[16px] cursor-pointer active:scale-[0.95]" @click="unApprovePost(dataForm.id)">
                    Hủy xác nhận
                </div>
            </div>
        </template>
    </el-dialog>
</template>

<script>
import axios from 'axios';
import { Link } from '@inertiajs/vue3'
import moment from "moment";
import { ElMessage, ElMessageBox } from 'element-plus'

export default {
    components:{
        Link,
    },
    data() {
        return {
            dialogVisible: false,
            dataForm: {},
            count_view: 0
        }
    },
    watch: {
        dialogVisible(value) {
            if(value == false) {
                this.dataForm = {}
            }
        }
    },
    methods: {
        moment,
        open(data) {
            this.dialogVisible = true;
            this.dataForm = data
            if(this.dataForm.is_approved) {
                axios.get(route('creator.posts.get-count-view', data.id))
                    .then(response => {
                        this.count_view = response.data.count_view
                    })
            }
        },
        approvePost(id) {
            ElMessageBox.confirm(
                `Bạn có muốn xác nhận bài viết đã chọn không?`,
                'Warning',
                {
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy bỏ',
                    type: 'warning',
                    draggable: true,
                }
            )
            .then(() => {
                axios.get(route('admin.posts.change-approved', id))
                    .then(response => {
                        this.dialogVisible = false
                        this.$emit('change-post')
                        ElMessage({
                            type: 'success',
                            message: 'Xác nhận bài viết thành công',
                        })
                    })
                    .catch(() => {})
            })
            .catch(() => {})
        },
        unApprovePost(id) {
            ElMessageBox.confirm(
                `Bạn có muốn hủy xác nhận bài viết đã chọn không?`,
                'Warning',
                {
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy bỏ',
                    type: 'warning',
                    draggable: true,
                }
            )
            .then(() => {
                axios.get(route('admin.posts.change-approved', id))
                    .then(response => {
                        this.dialogVisible = false
                        this.$emit('change-post')
                        ElMessage({
                            type: 'success',
                            message: 'Hủy xác nhận bài viết thành công',
                        })
                    })
                    .catch(() => {})
            })
            .catch(() => {})
        }
    },
}
</script>
<style>
#show-post .el-dialog__headerbtn {
    font-size: 24px !important;
}
#show-post.el-dialog {
    border-radius: 4px !important;
    position: fixed !important;
    left: 0;
    right: 0;
}
#show-post .el-dialog__header {
    position: fixed;
    width: 800px;
    background-color: #fff;
    border-bottom: 1px solid #ccc;
    margin-right: 0 !important;
    z-index: 100;
}
#show-post .el-select,
#show-post .el-date-editor {
    width: 100% !important;
}
#show-post .el-input__inner {
    margin-top: 2px;
}
#show-post .creator {
    margin: 0 12px;
    padding: 0 12px;
    border-left: 2px solid rgba(0, 0, 0, 0.55);
    border-right: 2px solid rgba(0, 0, 0, 0.55);
}
</style>

