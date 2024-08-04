import {useForm} from "@inertiajs/react";
import {UserProgressActions} from "@/Store/UserProgressSlice.js";
import Modal from "@/Components/Modal.jsx";
import InputLabel from "@/Components/InputLabel.jsx";
import TextInput from "@/Components/TextInput.jsx";
import InputError from "@/Components/InputError.jsx";
import TextArea from "@/Components/TextArea.jsx";
import SecondaryButton from "@/Components/SecondaryButton.jsx";
import PrimaryButton from "@/Components/PrimaryButton.jsx";
import {redirect} from "react-router-dom";
import NavLink from "@/Components/NavLink.jsx";

const EditArticle = ({ auth, article }) => {
    const { data, setData, put, processing, errors, reset } = useForm({
        title: article.title,
        body: article.body,
        publication_date: article.publication_date,
    });

    const editArticle = (e) => {
        e.preventDefault();

        put(`/articles/${article.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                redirect('articles');
            },
            onError: (errors) => {
                if (errors.title) {
                    reset('title');
                    titleInput.current.focus();
                }

                if (errors.body) {
                    reset('body');
                    bodyInput.current.focus();
                }

                if (errors.publication_date) {
                    reset('publication_date');
                    publicationDateInput.current.focus();
                }
            },
            onFinish: () => reset(),
        });
    };

    return(
        <form onSubmit={editArticle} className="p-6">
            <h2 className="text-lg font-medium text-gray-900">
                Edit article:
            </h2>

            <div className="mt-6">
                <InputLabel htmlFor="title" value="Title" className="sr-only"/>

                <TextInput
                    id="title"
                    type="text"
                    name="title"
                    value={data.title}
                    onChange={(e) => setData('title', e.target.value)}
                    className="mt-1 block w-3/4"
                    isFocused
                    placeholder="Title"
                />

                <InputError message={errors.title} className="mt-2"/>
            </div>

            <div className="mt-6">
                <InputLabel htmlFor="body" value="body" className="sr-only"/>

                <TextArea
                    id="body"
                    type="text"
                    name="body"
                    rows='10'
                    value={data.body}
                    onChange={(e) => setData('body', e.target.value)}
                    className="mt-1 block w-3/4"
                    placeholder="Body"
                />

                <InputError message={errors.body} className="mt-2"/>
            </div>

            <div className="mt-6">
                <InputLabel htmlFor="publication_date" value="Publication Date" className="sr-only"/>

                <TextInput
                    id="publication_date"
                    type="text"
                    name="publication_date"
                    value={data.publication_date}
                    onChange={(e) => setData('publication_date', e.target.value)}
                    className="mt-1 block w-3/4"
                    placeholder="Publication Date"
                />

                <InputError message={errors.publication_date} className="mt-2"/>
            </div>

            <div className="mt-6 flex justify-end">
                <NavLink href={`/articles`} active={route().current(`articles`)}>
                    Cancel
                </NavLink>

                <PrimaryButton className="ms-3" disabled={processing}>
                    Edit Article
                </PrimaryButton>
            </div>
        </form>
    );
}

export default EditArticle;
