import InputLabel from "@/Components/InputLabel.jsx";
import TextInput from "@/Components/TextInput.jsx";
import InputError from "@/Components/InputError.jsx";
import TextArea from "@/Components/TextArea.jsx";
import SecondaryButton from "@/Components/SecondaryButton.jsx";
import PrimaryButton from "@/Components/PrimaryButton.jsx";
import Modal from "@/Components/Modal.jsx";
import {useDispatch, useSelector} from "react-redux";
import {useRef} from "react";
import {useForm} from "@inertiajs/react";
import {UserProgressActions} from "@/Store/UserProgressSlice.js";

const CreateArticle = () => {
    const dispatch = useDispatch();
    const userProgress = useSelector(state =>  state.userProgress);
    const titleInput = useRef();
    const bodyInput = useRef();
    const publicationDateInput = useRef();
    const { data, setData, post, processing, errors, reset } = useForm({
        title: '',
        body: '',
        publication_date: '',
    });

    const closeModal = () => {
        dispatch(UserProgressActions.hideModal());

        reset();
    }
    const createArticle = (e) => {
        e.preventDefault();

        post(route('article.create'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: (errors) => {
                if (errors.title) {
                    reset('title', 'body');
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
        <Modal show={userProgress.name === 'add'} onClose={closeModal}>
            <form onSubmit={createArticle} className="p-6">
                <h2 className="text-lg font-medium text-gray-900">
                    Create new article
                </h2>

                <div className="mt-6">
                    <InputLabel htmlFor="title" value="Title" className="sr-only"/>

                    <TextInput
                        id="title"
                        type="text"
                        name="title"
                        ref={titleInput}
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
                        ref={bodyInput}
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
                        ref={publicationDateInput}
                        value={data.publication_date}
                        onChange={(e) => setData('publication_date', e.target.value)}
                        className="mt-1 block w-3/4"
                        placeholder="Publication Date, e.g. 2024-08-08"
                    />

                    <InputError message={errors.body} className="mt-2"/>
                </div>

                <div className="mt-6 flex justify-end">
                    <SecondaryButton onClick={closeModal}>Cancel</SecondaryButton>

                    <PrimaryButton className="ms-3" disabled={processing}>
                        Create Article
                    </PrimaryButton>
                </div>
            </form>
        </Modal>
    );
}

export default CreateArticle;
