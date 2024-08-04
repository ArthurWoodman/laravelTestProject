import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.jsx';
import {Head, useForm} from '@inertiajs/react';
import classes from './Articles.module.css';
import PrimaryButton from "@/Components/PrimaryButton.jsx";
import DangerButton from "@/Components/DangerButton.jsx";
import CreateArticle from "@/Pages/CreateArticle.jsx";
import {useDispatch} from "react-redux";
import {UserProgressActions} from "@/Store/UserProgressSlice.js";
import DeleteArticle from "@/Pages/DeleteArticle.jsx";
import EditArticle from "@/Pages/EditArticle.jsx";
import {useRef} from "react";
import {redirect, useNavigate} from "react-router-dom";
import NavLink from "@/Components/NavLink.jsx";

const Articles = ({ auth, articles }) =>
{
    const dispatch = useDispatch();

    const deleteArticleHandler = (article) => {
        dispatch(UserProgressActions.showDeleteArticleModal(article));
    }

    const showAddArticleDialog = (event) => {
        dispatch(UserProgressActions.showAddArticleModal());
    }

    return (
        <>
            <AuthenticatedLayout
                user={auth.user}
                header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Articles</h2>}
            >
                <Head title="Articles" />

                <div className="py-12">
                    <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className={classes.headerBuffer}>
                                <PrimaryButton className={classes.btn} onClick={(event) => showAddArticleDialog(event)}>Add</PrimaryButton>
                            </div>
                            {
                                articles && articles.length > 0 ?
                                    <>
                                        <ul className={classes.list}>
                                            {
                                                articles.map(article => (
                                                    <li key={article.title} className={classes.article}>
                                                        <h2><p>Title:</p> {article.title}</h2>
                                                        <h3><p>Body:</p> {article.body}</h3>
                                                        <div className={classes['article-footer']}>
                                                            <h4>User: {article.user.name}</h4>
                                                            <time>Published date: {article.publication_date}</time>
                                                        </div>
                                                        <div className={classes.controls}>
                                                            <NavLink href={`articles/${article.id}`} active={route().current(`article`)}>
                                                                Read
                                                            </NavLink>
                                                            <NavLink href={`articles/${article.id}/edit`} active={route().current(`article`)}>
                                                                Edit
                                                            </NavLink>
                                                            <DangerButton onClick={() => deleteArticleHandler(article)}>Delete</DangerButton>
                                                        </div>
                                                    </li>
                                                ))
                                            }
                                        </ul>
                                    </>
                                    :
                                    <p className={classes['header-entry-data']}>Add some articles in Admin or using API...</p>
                            }

                        </div>
                    </div>
                </div>
            </AuthenticatedLayout>
            <CreateArticle />
            <DeleteArticle />
        </>
    );
}

// should have been used with RouterProvider...
export default Articles;
//
// const getArticles = async () => {
//     const response = await fetch('http://localhost:8000/api/1.0.0/articles');
//
//     if (response.status === 422 || response.status === 401) {
//         return response;
//     }
//
//     if (!response.ok) {
//         JSON.stringify({message: 'Can not get articles!'}),
//         {error: 500}
//     } else {
//         const responseData = await response.json();
//
//         return responseData.articles;
//     }
// }
//
// export const loader = () => {
//     return defer({
//         articles: getArticles()
//     })
// }
