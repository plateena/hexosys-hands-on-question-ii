import Vue from "vue";
import VueJsonPretty from "vue-json-pretty";
import "vue-json-pretty/lib/styles.css";

new Vue({
    el: "#upload-section",
    data: {
        imageUrl: "",
        imageBinary: "",
        result: "",
        headers: {},
    },
    components: {
        VueJsonPretty,
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

        async postFile(event) {
            // let url =
            //     "https://asia-east2-falcon-293005.cloudfunctions.net/falcon";
            let url = "/api/moderate";

            try {
                let response = await axios.post(
                    url,
                    this.populateFormData(event),
                    {
                        headers: this.headers,
                    }
                );

                this.result = response.data;

                console.log({ result: response });
            } catch (error) {
                /* handle error */
                console.log({ error: error });
            }
        },

        // handle file drop
        fileDrop(event) {
            event.preventDefault();
            this.processFileChange(event.type, event.dataTransfer.files[0]);
        },

        // handle form data between file upload or url submited
        populateFormData(event) {
            if (event.constructor.name == "ProgressEvent") {
                this.headers = { "Content-Type": "image/jpeg" };
                return event.target.result;
            }

            this.headers = {
                "Content-Type": "multipart/form-data",
            };

            let formData = new FormData();
            formData.append("imageUrl", this.imageUrl);
            return formData;
        },
    },
});
