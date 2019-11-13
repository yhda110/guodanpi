<template>
  <v-container class="fill-witdh" fluid>
    <!-- <v-tabs >
          <v-tab v-for="item in tabslist" :key="item.id" @change="getnewdata(item.id)">{{item.name}}</v-tab>
    </v-tabs>-->
    <v-row align="center" justify="center">
      <v-col cols="16" sm="12" md="8">
        <v-card class="mx-auto" style="display:flex;flex-wrap:wrap;">
          <div style="padding: 10px 10px 0 0; box-sizing:border-box;">
            <el-input
              class="input-new-tag"
              v-if="inputVisible"
              v-model="inputValue"
              ref="saveTagInput"
              size="small"
              @keyup.enter.native="handleInputConfirm"
              @blur="handleInputConfirm"
            ></el-input>
            <el-button v-else class="button-new-tag" size="small" @click="showInput">+ New Tag</el-button>
            <span :key="tag.id" v-for="(tag,index) in dynamicTags">
              <el-tag
                v-if="tag.isupadate==0"
                closable
                :type="tag.is_del===1?'danger':'success'"
                :disable-transitions="false"
                @click="updateInfo(tag,index)"
                @close="handleClose(tag.id,tag.is_del)"
              >{{tag.tag_name}}</el-tag>
              <div  v-else>
                <el-input
                size="small"
                class="input-new-tag"
                ref="tag"
                @blur="closeInput(tag,index)"
                v-model="tag.tag_name"
                ></el-input>
                <input type="color">

              </div>

            </span>
          </div>
          <!-- <v-card-actions>
          <div class="flex-grow-1"></div>-->
          <!-- <v-row> -->
          <!-- <v-btn color="primary"  style="margin-right:10px;" @click="ischeck(radioGroup)">确认</v-btn>
          <v-btn color="primary"  @click="back" >取消</v-btn>-->
          <!-- </v-row> -->
          <!-- </v-card-actions> -->
        </v-card>
      </v-col>
    </v-row>

    <div class="text-center">
      <v-pagination v-model="page" :length="total" @input="getNext(page)"></v-pagination>
    </div>
  </v-container>
</template>
<script>
export default {
  data() {
    return {
      drawer: null,
      page: 1,
      total: 0,
      limt: 100,
      tabs: "", //选中的tab
      dynamicTags: [],
      inputVisible: false,
      inputValue: "",
      taglist: []
    };
  },
  components: {},
  created() {
    this.getdata(1);
  },
  methods: {
    handleClose(tag, type) {
      this.$axios
        .post("/api/admin/tag/deleteTag", {
          tag_id: tag,
          is_del: type == 1 ? 0 : 1
        })
        .then(res => {
          if (res.data.flag === true) {
            this.$message.success(res.data.msg);
            this.getdata(1);
          } else {
            this.$message.error(res.data.msg);
          }
        });
    },
    closeInput(tag, index) {
      if (tag.tag_name == this.taglist[index]) {
        console.log(1);
      } else {
        this.$axios
          .post("/api/admin/tag/updateTag", {
            tag_id: tag.id,
            tag_name: tag.tag_name
          })
          .then(res => {
            if (res.data.flag === true) {
              this.getdata(1);
            } else {
              this.$message.error(res.data.msg);
              this.getdata(1);
            }
          });
      }

      //
    },
    updateInfo(tag, index) {
      tag.isupadate = 1;

      this.$set(this.dynamicTags[index], index, tag);
      this.$nextTick(function() {
        console.log(this.$refs.tag);
        this.$refs.tag[this.$refs.tag.length - 1].$refs.input.focus();
      });
    },
    showInput() {
      this.inputVisible = true;
      this.$nextTick(function() {
        this.$refs.saveTagInput.$refs.input.focus();
      });
    },
    handleInputConfirm() {
      if (this.inputValue) {
        this.$axios
          .post("/api/admin/tag/insertTag", {
            tag_name: this.inputValue
          })
          .then(res => {
            if (res.data.flag === true) {
              this.getdata(1);
              this.inputVisible = false;
              this.inputValue = "";
            }
          });
      } else {
        this.inputVisible = false;
      }

      // let inputValue = this.inputValue;

      // if (inputValue) {
      //   this.dynamicTags.push(inputValue);
      // }
      // this.inputVisible = false;
      // this.inputValue = "";
    },
    getNext(number) {
      if (this.type) {
        this.getdata(number, this.type);
      } else {
        this.getdata(number);
      }
    },

    getnewdata(val) {
      this.page = 1;
      if (val) {
        this.tabs = val;
        this.getdata(1, val);
      } else {
        this.tabs = "";
        this.getdata(1);
      }
    },
    getdata(page) {
      var num = page * 1 - 1;
      this.taglist = [];
      this.$axios
        .get("api/admin/tag/getAll?limit=" + this.limt + "&offset=" + num)
        .then(res => {
          if (res.data.flag === true) {
            res.data.data.info.forEach(item => {
              this.taglist.push(item.tag_name);
            });
            this.dynamicTags = res.data.data.info;

            this.dynamicTags.forEach(item => {
              item.isupadate = 0;
            });
            console.log("8888888888", this.taglist);
            this.total = Math.ceil(Number(res.data.data.count) / this.limt);
          }
        });
    }
  }
};
</script>
<style>
.el-tag {
  margin-left: 10px;
  margin-bottom: 10px;
  cursor: pointer;
}
.button-new-tag {
  margin: 0 0 10px 10px !important;
  height: 32px;
  line-height: 30px;
  padding-top: 0;
  padding-bottom: 0;
}
.input-new-tag {
  width: 90px !important;
  margin-left: 10px;
  margin-bottom: 10px;
  vertical-align: bottom;
}
</style>
