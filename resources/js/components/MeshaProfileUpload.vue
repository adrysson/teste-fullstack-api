<template>
  <div>
    <div class="row justify-content-center" v-if="!selected">
      <div
        @click="launchFilePicker"
        class="select-avatar-div box d-flex justify-content-center flex-column"
      >
        <h4 class="text-center" style="color: #5d5f61">Selecionar foto</h4>
      </div>
    </div>
    <div v-show="avatar" class="row">
      <div class="col-md-12">
        <img
          class="rounded-circle mx-auto d-block"
          :src="avatar"
          id="avatar"
          alt=""
        />
      </div>
    </div>

    <div v-show="croppie" id="croppie"></div>

    <div v-show="actionButtons">
      <div class="row">
        <div class="col-md-12 text-center mt-2">
          <button class="btn btn-light" @click.prevent="launchFilePicker">
            <i class="fas fa-mouse-pointer" style="padding-right: 3px"></i
            >Selecionar
          </button>
          <button
            class="btn btn-outline-success"
            v-if="!avatar"
            @click="save()"
          >
            <i class="fa fa-camera" style="padding-right: 3px"></i>Salvar!
          </button>
        </div>
      </div>
    </div>

    <input
      type="file"
      ref="file"
      name="file"
      @change="onFileChange($event.target.name, $event.target.files)"
      style="display:none"
    />

    <input type="text" name="avatar" :value="avatar" hidden />
    <p v-if="errorDialog" class="text-danger text-center mt-2">
      {{ errorText }}
    </p>
  </div>
</template>
<script>
import Croppie from 'croppie';
import 'croppie/croppie.css';

export default {
  props: ['url'],
  data() {
    return {
      errorDialog: null,
      errorText: '',
      maxSize: 4096,
      selected: false,
      avatar: this.url ? this.url : null,
      saving: false,
      saved: false,
      croppie: null,
      actionButtons: false
    };
  },
  mounted() {
    this.selected = this.hasUrl;
    this.actionButtons = this.hasUrl;
  },
  computed: {
    hasUrl() {
      return this.url ? true : false;
    }
  },
  methods: {
    launchFilePicker() {
      this.$refs.file.click();
    },
    onFileChange(fieldName, file) {
      const { maxSize } = this;
      let imageFile = file[0];
      if (file.length > 0) {
        let size = imageFile.size / 1024 / maxSize;
        //check if user actually selected a file

        if (!imageFile.type.match('image.*')) {
          // check whether the upload is an image
          this.errorDialog = true;
          this.errorText = 'Por favor, escolha um arquivo de imagem.';
        } else if (size > 1) {
          // check whether the size is greater than the size limit
          this.errorDialog = true;
          this.errorText =
            'Seu arquivo é muito grande. Por favor escolha uma imagem menor.';
        } else {
          this.errorDialog = false;
          this.errorText = '';
          // Append file into FormData & turn file into image URL
          // let formData = new FormData()
          let imageURL = URL.createObjectURL(imageFile);
          // formData.append(fieldName, imageFile)

          // Emit FormData & image URL to the parent component
          // this.$emit('input', {formData, imageURL})
          // this.avatar = {formData, imageURL}
          // this.setUpCroppie(this.avatar.imageURL)
          this.selected = true;
          this.setUpCroppie(imageURL);
        }
      }
    },
    setUpCroppie(image) {
      let el = document.getElementById('croppie');

      if (this.croppie) {
        this.croppie.destroy();
      }

      this.avatar = null;
      this.croppie = new Croppie(el, {
        viewport: { width: 200, height: 200, type: 'circle' },
        boundary: { width: 220, height: 220 },
        showZoomer: true,
        enableOrientation: true
      });
      this.actionButtons = true;

      this.croppie.bind({
        url: image
      });
    },
    save() {
      return new Promise(resolve => {
        if (this.croppie) {
          this.croppie
            .result({
              type: 'canvas',
              size: 'viewport',
              circle: false
            })
            .then(response => {
              this.avatar = response;
            });

          this.croppie.destroy();
          this.croppie = null;
        }
        resolve();
      });
    }
  }
};
</script>
<style>
#avatar {
  max-width: 100%;
  border: 1px solid #919fa1;
}

.select-avatar-div {
  width: 200px;
  height: 200px;
  border-radius: 50%;
  border: 1px solid #919fa1;
  background-color: #efeff0;
}
</style>
