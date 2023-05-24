<template>
    <el-dialog v-model="dialogVisible" id="category-post" class="bg-[#f4f1f8] h-[60%] overflow-scroll" width="600px" 
     style="margin-top: 124px !important;" :show-close="true">
        <template #header>
            <div class="text-center font-bold text-[18px] text-[#9f224e]">
                Kết quả phân loại
            </div>
        </template>
        <div class="flex mt-[38px]">
            <div class="list-find-category" v-if="listCategory.length != 0">
                <div class="text-left text-[16px] font-bold text-[#000] mb-[8px]">Chọn chủ đề bài viết</div>
                <div class="mt-[6px] mx-[12px] row">
                    <template v-for="category in listCategory">
                        <div class="col-6">
                            <el-button class="w-[100%] mt-[8px]" @click="this.categorySelect = category.slug">
                                <span class="font-bold">{{ category.name }}</span>
                                :
                                <span class="ml-[4px] font-bold"> {{ category.value }} %</span>
                            </el-button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <template #footer>
            <div class="pb-[12px] pt-[8px]">
                <div class="flex justify-center text-[14px]">
                    <div class="cursor-pointer flex justify-center items-center w-[180px] rounded-[4px] bg-[#9f224e] py-[4px] h-[32px] text-[15px] text-white"
                        @click="addPost">
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
import moment from "moment";

export default {
    components:{
        Link,
    },
    data() {
        return {
            dialogVisible: false,
            listCategory: '',
            dataForm: '',
            categorySelect: ''
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
            this.listCategory = data
        },
        addPost() {
            if(this.categorySelect) {
                this.dialogVisible = false;
                this.$emit('change-category', this.categorySelect)
            }
        }
    },
}
</script>
<style>
#category-post .el-dialog__headerbtn {
    font-size: 24px !important;
}
#category-post.el-dialog {
    border-radius: 4px !important;
    position: fixed !important;
    left: 0;
    right: 0;
}
#category-post .el-dialog__header {
    position: fixed;
    min-width: 600px !important;
    width: 550px !important;
    background-color: #fff;
    border-bottom: 1px solid #ccc;
    margin-right: 0 !important;
}
#category-post .el-select,
#category-post .el-date-editor {
    width: 100% !important;
}
#category-post .el-input__inner {
    margin-top: 2px;
}
#category-post .creator {
    margin: 0 12px;
    padding: 0 12px;
    border-left: 2px solid rgba(0, 0, 0, 0.55);
    border-right: 2px solid rgba(0, 0, 0, 0.55);
}
</style>

