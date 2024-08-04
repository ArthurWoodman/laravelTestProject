import {configureStore} from "@reduxjs/toolkit";
import UserProgressSlice from "./UserProgressSlice";
import ArticleSlice from "./ArticleSlice.js";

const store = configureStore({
    reducer: { movie: ArticleSlice.reducer, userProgress: UserProgressSlice.reducer }
});

export default store;
