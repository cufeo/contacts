import * as axios from "axios";

export const ContactsApi = {
    endpoint: '/api/contacts',
    get(contactId) {
        return axios.get(`${this.endpoint}/${contactId}`);
    },
    update(contact) {
        return axios.put(`${this.endpoint}/${contact.id}`, contact);
    },
    create(contact) {
        return axios.post(`${this.endpoint}`, contact);
    },
    delete(contactId) {
        return axios.delete(`${this.endpoint}/${contactId}`);
    }
};