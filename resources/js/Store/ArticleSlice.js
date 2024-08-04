import {createSlice} from "@reduxjs/toolkit";

const ArticleSlice = createSlice({
    name: 'article',
    initialState: { items: [] },
    reducers: {
        addArticleItem(state, action) {
            // there is no need to work with the state at this time...
        },
        removeArticleItem(state, action) {
            // there is no need to work with the state at this time...
        }
    }
});

export default ArticleSlice;

export const ArticleActions = ArticleSlice.actions;
