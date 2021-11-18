import Vue from "vue";

new Vue({
    el: "#upload-section",
    data: {
        imageUrl: "",
        imageBinary: "",
        result: "",
        headers: {},
    },
    mounted() {},
    methods: {
        processUrlForm(event) {
            this.postFile(event);
        },
        processFileChange(name, file) {
            var reader = new FileReader();
            reader.addEventListener("load", this.postFile);
            reader.readAsArrayBuffer(file);
        },
        postFile(event) {
            // let url =
            //     "https://asia-east2-falcon-293005.cloudfunctions.net/falcon";
            let url = "/api/moderate";
            axios
                .post(url, this.populateFormData(event), {
                    headers: this.headers,
                })
                .then((response) => {
                    console.log({ result: response });
                })
                .catch((error) => {
                    console.log({ error: error });
                });
        },

        populateFormData(event) {
            if (event.constructor.name == "ProgressEvent") {
                this.headers = { "Content-Type": "image/jpeg" };
                return event.target.result;
            }

            this.headers = {
                "Content-Type": "application/x-www-form-urlencoded",
            };
            let formData = new FormData();
            formData.append("imageUrl", this.imageUrl);
            return formData;
        },
    },
});
