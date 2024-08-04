import InputLabel from "@/Components/InputLabel.jsx";
import TextInput from "@/Components/TextInput.jsx";
import InputError from "@/Components/InputError.jsx";
import SecondaryButton from "@/Components/SecondaryButton.jsx";
import DangerButton from "@/Components/DangerButton.jsx";
import Modal from "@/Components/Modal.jsx";
import {useDispatch, useSelector} from "react-redux";
import {UserProgressActions} from "@/Store/UserProgressSlice.js";
import {useForm, usePage} from "@inertiajs/react";
import {useEffect, useRef} from "react";

const DeleteArticle = () => {
    const userProgress = useSelector(state => state.userProgress);
    const dispatch = useDispatch();
    const deleteInput = useRef();

    const {
        data,
        delete: destroy,
        processing,
        reset,
        errors,
    } = useForm();

    const closeModal = () => {
        dispatch(UserProgressActions.hideModal());
    }

    const deleteArticle = (e) => {
        e.preventDefault();

        destroy(`/articles/${userProgress.deleteBuffer.id}`, {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: () => {
                // handle
            },
            onFinish: () => reset(),
        });
    };

    return (
        <Modal show={userProgress.name === 'delete'} onClose={closeModal}>
            <form onSubmit={deleteArticle} className="p-6">
                <h2 className="text-lg font-medium text-gray-900">
                    Are you sure you want to delete "{userProgress.deleteBuffer.title}" article?
                </h2>

                <div className="mt-6 flex justify-end">
                    <SecondaryButton onClick={closeModal}>Cancel</SecondaryButton>

                    <DangerButton className="ms-3" disabled={processing}>
                        Delete Article
                    </DangerButton>
                </div>
            </form>
        </Modal>
    );
}

export default DeleteArticle;
