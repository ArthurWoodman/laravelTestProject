import classes from './Article.module.css';
import NavLink from "@/Components/NavLink.jsx";
import {Head} from "@inertiajs/react";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.jsx';

const Article = ({ auth, article }) => {
    const articleTitle = `Article: "${article.title}"`;
    const cancelEditArticleHandler = () => {
        return route('/articles');
    }

    return(
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">{articleTitle}</h2>}
        >
            <Head title={articleTitle} />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="mt-6">
                            <div className={classes['article-body']}>
                                {article.body}
                            </div>
                        </div>

                        <div className={classes['article-footer']}>
                            <h4>User: {article.user.name}</h4>
                            <time>Published date: {article.publication_date}</time>
                        </div>

                        <div className="mt-6 flex justify-end">
                            <NavLink href={`/articles`} active={route().current(`articles`)}>
                                Cancel
                            </NavLink>

                            <NavLink href={`/articles/${article.id}/edit`}
                                     active={route().current(`articles/${article.id}/edit`)}>
                                Edit
                            </NavLink>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

export default Article;
