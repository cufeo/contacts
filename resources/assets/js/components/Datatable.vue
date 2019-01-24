<template>
    <div class="card">
        <div class="card-body">
            <slot></slot>
            <table :id="id" class="table" style="border-collapse: collapse !important;">
                <thead>
                <tr>
                    <th v-for="column in columns" :key="column.name">{{column.label}}</th>
                    <th> ACTIONS</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</template>

<script>
    import * as Vue from "vue";
    import DatatableControls from "./DatatableControls";

    export default {
        name: 'Datatable',
        props: ['columns', 'resourceUrl'],
        components: {DatatableControls},
        data() {
            return {
                id: 'datatable',
                datatable: null,
                records: [],
                controls: [],
            }
        },
        mounted() {
            this.bootstrap();
        },
        computed: {
          element(){
              return $(`#${this.id}`);
          }
        },
        methods: {
            // Setup datatable library and
            bootstrap() {
                const vm = this;
                vm.$on('refresh', vm.onRefreshRequested);
                vm.datatable = vm.element.DataTable({
                    processing: true,
                    serverSide: true,
                    serverMethod: 'POST',
                    ajax: {
                        url: vm.resourceUrl,
                        type: 'GET',
                        dataSrc: function (json) {
                            vm.records = json.data;
                            return vm.records;
                        }
                    },
                    columns: vm.columns
                        .map(column => ({data: column.name, visible: column.label}))
                        .concat([
                            {
                                "targets": -1,
                                "data": null,
                                "render": function (data, type, row, meta) {
                                    return '<span>...</span>';
                                }
                            }
                        ]),
                    language: {
                        search: '',
                        searchPlaceholder: "Search",
                        processing: ''
                    },
                    drawCallback: vm.onRecordsRefreshed
                });
            },

            // Callback when datatable refresh is requestedt
            onRefreshRequested(){
               this.datatable.draw();
            },

            // Called everytime datatable records get refreshed
            onRecordsRefreshed(settings) {
                const vm = this;
                let totalRows = vm.records.length;
                let lastColumnIndex = settings.aoColumns.filter((column) => column.bVisible).length;
                vm.$emit('refreshed', vm.records);
                vm.renderRecordsControls(totalRows, lastColumnIndex);
            },

            // Attach an instance of DatableControls to every record
            renderRecordsControls(totalRows, columnIndex) {
                const vm = this;
                // Destroy old controls
                vm.controls.forEach((control)=> {
                    control.$off();
                    control.$destroy();
                });
                // vm.controlsListeners.forEach((control) => control.$off());
                for (let row = 0; row < totalRows; row++) {
                    // let recordId = vm.records[row - 1].id;
                    let control = new (Vue.extend(DatatableControls))({
                        key: row,
                        parent: vm,
                        propsData: {
                            id: row
                        },
                        mounted(){
                            const controls = this;
                            vm.bubbleUpControlsEvents(controls);
                        }
                    }).$mount(`#datatable tbody tr:nth-child(${row + 1}) td:nth-child(${columnIndex}) span`);
                    vm.controls.push(control);
                }
            },

            // Attach listeners to controls event and emit them again
            bubbleUpControlsEvents(controls){
                const vm = this;
                controls.$on('delete', function (row) {
                    vm.$emit('delete-record', vm.records[row]);
                });
                controls.$on('edit', function (row) {
                    vm.$emit('edit-record', vm.records[row]);
                });
                controls.$on('show', function (row) {
                    vm.$emit('show-record', vm.records[row]);
                });
            },
        }
    }
</script>

<style>

    h6 {
        margin-bottom: 1rem;
    }

    .card {
        overflow: auto;
    }

    table {
        width: 100% !important;
        /*margin-top: 0.8rem !important;*/
    }

    .table thead th {
        border-top: initial;
        border-bottom-width: 1px !important;
        white-space: nowrap;

    }

    div.dataTables_wrapper div.dataTables_processing {
        position: absolute;
        background-image: url("data:image/gif;base64,R0lGODlhRgBGAPf/AJq++ff6/v///63K+nWm94mz+Huq95y/+eTu/YCt95O5+bPO+83f/b7V+7rT+4Gu+HSl98ba+53A+e/0/oy1+GKZ9rXP+sDW+5S6+ejw/vD2/rjR++vy/pG4+WWc90OF9ODr/uLs/ery/srd/KjH+l2X9s/g/FqV9Wug98PY+1WS9X6r98fa/Y+2+ZO6+Ja7+dzo/Xyr+PD0/qrI+tPj/Ji8+fr7/qDC+pe8+d7q/rLN+qfF+kmJ9Iq0+IWw+NXk/dro/bjR/KTE+YSw96TE+sTY+6LD+6vI+qbG+trm/Yey+ViT9U+O9efv/tXj/VOQ9WCY9Xqp92ed9nio926i93yr92ie92ac9nGj926h93in92+i92yg93mo93mp92mf92me93Ok99fl/Xan99jm/Wid9/T4/nup922h92qf97fR+3Kk9/7+/3en9/f5/v3+/7bQ+2ac93Ol93Ci99bk/Wuf9+jw/fn7/vv8/3Cj94ax+Gme9vL3/nyq9/7//3Gk936s92Wb9qLD+X+s94aw+Pz9/5G3+fb5/pC3+bHM+6nI+n+t9/z+//r8//z9/vX5/vr8/rDM+2Sa97zT+2qe9/j7/nip94ey99Dh+2ie9uPt/Wec9muf9mid9vv9/vL2/7HN+qDB+Wqe9vn7//P3/mec9/b5/2ed99nn/dnm/X2r98/f/Gyg9tfl/O3z/vv9/2yh9//+/22g98Xa+9jm/Mfb+7fQ+4ex+LDM+rbQ+tnm/KfG+WSb9mmd9o+3+Onx/vP4/v39/4Ov+Pf6//j7//P3/22h9o21+Heo94ax997q/fz+/m+i9mmf9uHs/Wyh9m2g9tbl/brS+2qf9q7L+mue9pq9+fL2/vX4/nam92Oa9vn8/sze/PT4/W6i9vz9/WWc9bLN/HCj9mmd9aHC+XKk9trn/8fb/LTP+73U+/X5/2ad9pm9+dfm/PL3//X4/7HN+7DN+7HM+t3p/d7p/Zu/+n+s9sLY/f3+/nqo9+Hs/qXF+myf9mSb9W+j9////yH/C05FVFNDQVBFMi4wAwEAAAAh/wtYTVAgRGF0YVhNUDw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTQgKE1hY2ludG9zaCkiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NjU3RkM3NzhBNkE1MTFFNDk3QjlCRTUzOTRDNEU5NTciIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NjU3RkM3NzlBNkE1MTFFNDk3QjlCRTUzOTRDNEU5NTciPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo2NTdGQzc3NkE2QTUxMUU0OTdCOUJFNTM5NEM0RTk1NyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo2NTdGQzc3N0E2QTUxMUU0OTdCOUJFNTM5NEM0RTk1NyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PgH//v38+/r5+Pf29fTz8vHw7+7t7Ovq6ejn5uXk4+Lh4N/e3dzb2tnY19bV1NPS0dDPzs3My8rJyMfGxcTDwsHAv769vLu6ubi3trW0s7KxsK+urayrqqmop6alpKOioaCfnp2cm5qZmJeWlZSTkpGQj46NjIuKiYiHhoWEg4KBgH9+fXx7enl4d3Z1dHNycXBvbm1sa2ppaGdmZWRjYmFgX15dXFtaWVhXVlVUU1JRUE9OTUxLSklIR0ZFRENCQUA/Pj08Ozo5ODc2NTQzMjEwLy4tLCsqKSgnJiUkIyIhIB8eHRwbGhkYFxYVFBMSERAPDg0MCwoJCAcGBQQDAgEAACH5BAUDAP8ALAAAAABGAEYAAAj/AP8JHEiwoEGDAhIqXMhQwMGHECNKLNiwokWFEzNqJHixo8eNIA96HPkxZEiSKC+a1JiypcqVEF3KrAgT4cybDWsKxMmTYc2eQDGaDFrol6sJpPDgPAnUD4gSUK6s6TOg0M2NQROCWATBy5gSGHiyzDphDy8uc6SEEjsxa8IMEvYJwcDsQE+Jbhsy4kAPaMS8gIWKDEx4MOHCFA8j5qg4cOLGjgdCXjz58M7KkTE71sy5s+fPoEOLHk26tOnTqFOrXs26tevXsGPLJv1vts/atoXmFrybse3HsW3KFh6cuOuYr/8mV846Y3Pnq7GmZmp6ZXWYtHWK1ulbM3fjkL8/Epwsnvni8sjzogfpd/3PlOgDAgAh+QQFAwD/ACweAAoADAAGAAAIPAAFDHM1oeAEDqMEKCT2QIWHK1c8fFigUKEeNF4yevGAoaIAJSXSoECRRkVHhWwk1CCxYweJAik8ypwZEAAh+QQFAwD/ACwcAAoACwAGAAAIPAD//StUTOA/EaYECADSIdmfQAKZzGAjwIwKaGOiCNwST+G/F5nmcBG4BJ1HGqcUCBHIjptHPE0YGZwZEAAh+QQFAwD/ACwWAAoAEAAIAAAIXAD/CRxI8B8HPgIEFGSTBB2AgSoGFUoo0FWDY23AfMEicA4iNgrNRDIgZQsBL1HGCDyhh6KIJ8+qaMlCJcwKDP8k2KL470AgLlQ6WAjhqiBBXxjIGDXKJsNSowEBACH5BAUDAP8ALA8ACgAVAA4AAAiBAP8JHEiw4L8JbAQIMMhw4BAVLIIpbNhwnRGFCw0eShTFoIcxEw1iGiJljUEFBwplHMhGUZotBqagKPiGoStEUsZUmfPnAMWBd5RIqTLllB4TPwUWeiGlDwEuRwIkFbirV5SnF6YKdHBiSlFbWgWGKNCs34KwAwulE4S2IJu2/wICACH5BAUDAP8ALAoACgAYABgAAAiXAP8JHEiwYEFiAgQYXMiw3AU2fhhKHNjpQaGJGCGUSIFx4qACFhR2ZOhopMmTKFOqnJhCSpSV/zRhWRPli8pXiLhUASNBZYNSZ/KscJPyjrAtU6iYUDnLSp8voVTGOkalTR4QKmG0IYDGxUpQnaJQ4ahSAZcpbTKobJQgDARCZlROgIJGUg9GKl0NqueLxMpRjdj8c7UyIAAh+QQFAwD/ACwKAAoAGAAoAAAIvQD/CRxIsGBBAQYTKhxAwp2Ahwoj/tOGZhhEiQqRPdNxEWNCXi0KIfSoUMNIkihTqlzJsqXLlzBjypxJs6bNmwM/cZqCpUNLO4GiZDnQUoyVKCh2tKRzVMqCpUcDOSh69Aqcn0HTKGqpcwoVAC1HAQoDQY+blgq4RJGjqaWOPV6yTGqZCsKYLIjYsITUI88UNGRaNihThQuOljK0hPG6ym2ZM1gAHWL5SNicKiUUBGCJqcKJQRYytEyhqZHKgAAh+QQFAwD/ACwKAAsAFgAxAAAIyAD/CRxIsOC/V5pGCRBgsCFBJQ8WMnToEAsvRQspUpRywlVGjQ0HLJsIsqTJkyhTqgTJZuVADS5jypxJs6bNmzhz6tzJs6fPn0CDCp255d8XAH5U7hkTxUoklQOkGLBE6UhKGy/K9NESR5AjlBouxTnjBQwAMyjNKKhjIMqXQSNQVnrhYUyVOVQOkDn5ZgEKKga6/O1wwU6hkqsISQlTRUsWKlNUwQTpJtEUMFi0RGnT8SSHBT62SMm04lfKALXIJRhDzOUbmAEBACH5BAUDAP8ALAoACwAlADEAAAi8AP8JHEiw4D8BCA0qXMhwILUQAhpKnPjvBIWEFDMWjDLuHkaNGrlA2TcqIsiTKFOqXMmypcuVJl8yjCmzps2bOHPq3Mmzp8+fQIMKHUq0qNGjSJMqXcq0qdOnUEHSuMDSxkIbdi500KaFpSEScNSIVQOHhKEpVLJgyeZM5RQsaaRcmXtFShosU6JoycPjB5uUW8ZEGUx48JgtUrb4wEVKZYE/kqxInizpTwFytQK05KOJjpjPYuho4vPGYEAAIfkEBQMA/wAsFwAMACAALgAACKcA/wkY+K+gwYMIEyq8cYGgwocQDXrYY85hxIsHq2xSMlAAxo8FvVkjBbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSvSlnwHYZkY64cNVzCOULPGL8culI0FxtPRJY4gkSzMAwHg5E+eShpYjBn2JYqCOAjMsyRygMqfKGA8vKrWkgqKLAb4L3rjUUiWMFEKrYGIBMyWRG5kLOEAMCAAh+QQFAwD/ACwVAA0AJgAmAAAIoQD/3Tkk4J/BgwgTKlyo0MSieQIKMpxIMWGdTVuiSazIcaIqKIAmbOxIEuGtVa9KqlzJsqXLlzBjypxJs6bNmzhz6tzJs6fPn0CDCh1KtKhMDQbf7NQkZ0CGXzsnBeq0RZpONoiytEHhRCcZNFPyFICkEweXKmUa6FxFZUoYLRNyHgKE5UwZHToBfKmyRdgjnVaijNmDiacBKQN6lnlhA2FAACH5BAUDAP8ALBQADQAoAB4AAAiKAP/9EyBAoMGDCBMqXCiQYEGGECMiPHDBocSLEK+UmGERo0eEck7QIfixpEA0/7qZXMmypcuXMGPKnEmzps2bOHPq3Mmzp8+fB5WJ+JXBxk55vVaUibbTFhQIm37shHOlCwoyOxWl6bKJw04AVNqwMqXTjR4IaxY14hmljgueWbxkWtBzDARU/wICACH5BAUDAP8ALBIADwAqABUAAAh9AP8JICXgn8GDCBMqXMjQIIdQwwQUbEixosICKnxokGixo8UqFZRwmOix5MIsDfCZXMmypcuXMGPKnEmzps2bOGG+yUnR1Tw1FGbxTEimE5UTBYYidKNHzppFeJQelICmjahhUg0O+NJFCoKs/9Rc6QKLBtgNY1GQOZtWTEAAIfkEBQMA/wAsEQAPACoAEAAACHoA/wkUILCgwYMIEyo0KIANwYUQIyrEJaCixIsXVfRw+BCjx4Oi/pUgMqzjx5MCS8ELhrKly5cwTxaKiVIDA5oXR3GghqWNGZwR8bA7MSbTBaARaaDIN4eCJ6QL8VCgMkZOK6gLLXCJIgUUVoUgwkRBgeFrwgD/IAgMCAAh+QQFAwD/ACwQABAAKQALAAAIbgD/CRQgsKDBgwgTKiwogODChxATCnjX0GHEiwt34ChWEaNHgYcKngKnRwMbix8hDjuSzuAJB2xSRvSjLwYUUTAKhpJ5cdSLEiuyHCvE86OrKGsMSHFQ9GMELl7+VWnqUQAGNFWkUPX441+XfwEBACH5BAUDAP8ALA4AEAApAAgAAAheAAUIHEiwoMGDCAshXMiwocBzBzw5nNhwFLsKFV7cocjR4CwV2QxsCiWwSI6OHQV5iBJFigME005YQEmRjSAwUeTIQoSmTYkGNCfiMYRCVZZSZ/6oIhV04gQvawwEBAAh+QQFAwD/ACwNAA8AKgAoAAAIfAD/CRxIsKDBgwhZIFzIsKFAXCUA3HFIsWEhOM3alGFXseNBYgcCdYlyxaPJggsyTYlSUICAkxXJGWTj8iXMkyTI1Lzp8cKJfjs42ORZEQsESdyIKl3KtKnTp1CjSp1KtarVq1izat3KtavXr2DDih1LtqzZs2jTci3kJyAAIfkEBQMA/wAsDAANACsAKgAACI8A/wkcSLCgwYMF2ZxzhLChw4cCRRjTQwuiRYvD5EDLkkXaxY8IU6hYUwUCmAMgUw58NWLFlyhRSqmc+Y/PvzpTaOrUobMnQgE+VQoYGhQknqFEi0IsMgypUognKMz69fRhC2MorsCoCjGLglhcw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rde3BoQAAh+QQFAwD/ACwLAAwAJQASAAAIagAFCBxIsKDBgwSBneODsKHDg9xOrNjA6KFFhI18YPnTicLFjwOJBVnCxcsZLiBT3sFUgwqWKiljCqi14ovMmHwO3NzJs6fPn0CDCh0K1FUwogXn5aHQAAZSgaC0+Tux4akABVymtGliNSAAIfkEBQMA/wAsCwALACMAFAAACGAA/wkcSLCgwYMDi21DyLChQXT2UpBySPFgBgicpAyqyHFggRJRqmDpyPHQAGFf0JBcaQLHypcwY8qcSbOmzZs4c+p8KUDAToE9g+7ERGOCAHU7e5wQZuTXz39ZIGjYGRAAIfkEBQMA/wAsCgALACMAFwAACF8ABQgcSLCgwYMDhykLhrChw4J+DPQo8rAiwnMoqEixyJFgqCVRqnQcGSHKFzQjRzYygSOly5cwY8qcSbOmzZs4c+rcybPnzjc9TdWDk0GDziZMwoCxplOMlSlSyOkMCAAh+QQFAwD/ACwKAAsAIgAaAAAIaAD/CRxIsKDBg//YkBgAww/ChxAHboGixVfEiwabjCFAYAvGjwKVlZjmEeTHAO1w3TLJ8g7LlzBjypxJs6bNmzhz6tzJs6dPmAJ6Chg6VCfRojlJIRjaSCcmXhgkjNjJK8uTBT0D7QwIACH5BAUDAP8ALAoACgAgAB0AAAhhAAUIHEiwoMGDwYYdXMiw4BtqfwDkakjxIAYPYNJU3DjQC6coXjhudNWiy5osIjlO0IUrpcuXMGPKnEmzps2bOHPq3Mmzp8+fQIMKHQqTzcBCODXs2DDgHM4JKrQtaYEzIAAh+QQFAwD/ACwKAAoAHgAlAAAIbAAFCBxIsKBBgxP44DnIsKHAQ2lEDXFIsaAGH1v+VNwoUE6nMRwrvtnVYkXIjctcnVzJsqXLlzBjypxJs6bNmzhz6tzJs6fPn0CDCh1KVCCbmxpUyhw2KhyvFjM1UKggLs+qmQPKnMEC6NDLgAAh+QQFAwD/ACwMACoACwANAAAILAAFCBxIsKDBgwgTKlzIsKHBHN8QjjiFwyCeEaLyoDA4jF0JZFUOFlqAgkpAACH5BAUDAP8ALBIACgAVADIAAAh5AP8JHEiw4MBhv0gZXEiQjS0mWBhK/CdtDYGJDC+c2IJxIZ0XGDoyZCSypMmTKFOqXMmypcuXMGPKnEmzps2bOHPq3Mmzp0+TAgSkDBoUKFGiJY8qFam0qdCJTon+2GdLYlQBRph0bOonAUemRK+p0IZSQCFnTgwGBAAh+QQFAwD/ACweAAoAHgAyAAAIlwD/CRz4y9XAgwgTKhToqkSJNQsjRswgLM8YiRgRNtqjbU7Gj//M3AgFsqTJkyhTqlzJsqXLlzBjypxJs6bNmzhhVuMj8wqwmFNEDQMqyhRRozCDIoUphWdMYz9zSp1KtarVq1izusQWkw2mKGleCijEoMSZlQLSqhWgpAKXjGvjyuUThQFcuXjTFrubVy7IvnFNAhaQMiAAIfkEBQMA/wAsIgAKABgALwAACH0A/wHjMOGfwYMIEyZ8c4TJFYUQIwbZ4yWiRYRBnnC5yHEEACEcQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rc+e9Osi0qQSjhxaoHyjeFyDFxcFKAUwE5NJh8+nQqVadWqWatSvIqV5FesYINK6Br2KZa0ZZVuTZiQAAh+QQFAwD/ACwhAAoAGwAkAAAIcwD/Ffrl6p/BgwgTKjToB0QJKAsjSgSxCILEiwkn7OGFseO/DBL2eRxJsqTJkyhTqlzJsqXLlzBjypxJsyZMAFf+VDC1clebAlmarNRkho+GUSsFKFWadKmApkuhMlXp9GnKqlZRVpWa9SRWqluvOnU5NSAAIfkEBQMA/wAsMwAVAAkADQAACCsAwUSRkwWVgIMCOqCAUALRKIQTmH1J9OsRQgEhTF3cyLGjx48gQ4oceTAgACH5BAUDAP8ALC4ADwALAAoAAAgoAM9wySINkoCDCLEA2tTmF0KEgyqsQ/UQ4bUaYipq3Mixo8ePIDUGBAAh+QQFAwD/ACwpAAsACgAIAAAIIwBzzQsmoKBBAWl4WThoEJm2dAwLdlrzK6IAJPssatzI8WBAADs=");
        background-repeat: no-repeat;
        background-position: center;
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(4, 0, 0, 0.12);
        /*border: none;*/
        margin-left: -3rem;
        width: 70px;
        height: 70px;
        top: 50%;
        left: 50%;
        margin-top: -26px;
        text-align: center;
        padding: 1em 0;
        content: "";
    }

    @keyframes spinner-border {
        to {
            transform: rotate(360deg);
        }
    }

    div.dataTables_wrapper::after {
        display: inline-block;
        width: 70px;
        height: 70px;
        vertical-align: text-bottom;
        border: 5px solid currentColor;
        border-right-color: transparent;
    / / stylelint-disable-next-line property-blacklist border-radius: 50 %;
        animation: spinner-border .75s linear infinite;
    }

    #datatable_length > label {
        font-weight: 500;
    }

    input[type="search"] {
        padding: 1.2rem !important;
        font-size: inherit;
    }

    input[type="search"]::placeholder {
        opacity: 0.6;
    }

    .custom-select {
        height: 2.4rem !important;
    }
</style>
