import {createSlice} from "@reduxjs/toolkit";

const UserProgressSlice = createSlice({
    name: 'userProgress',
    initialState: {
        name: '',
        deleteBuffer: {id: '', title: ''},
        editBuffer: { article: { title: '', body: '', publication_date: '' }}
    },
    reducers: {
        showAddArticleModal(state) {
            state.name = 'add';
        },
        showEditArticleModal(state, action) {
            state.name = 'put';
            state.editBuffer.article = action.payload;
        },
        showDeleteArticleModal(state, action) {
            state.deleteBuffer.id = action.payload.id;
            state.deleteBuffer.title = action.payload.title;
            state.name = 'delete';
        },
        hideModal(state) {
            state.name = '';
        }
    }
});

export default UserProgressSlice;

export const UserProgressActions = UserProgressSlice.actions;
