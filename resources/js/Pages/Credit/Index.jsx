import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.jsx";
import {Head} from "@inertiajs/react";
import CreditPricingCards from "@/Components/CreditPricingCard.jsx";

export default function Index({ auth, packages, features, success, error }){
    const availableCredits = auth.user.available_credits;


    return <AuthenticatedLayout
        user={auth.user}
        header={
            <h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Buy More Credits</h2>
        }
    >
        <Head title="Credits" />

        <section className="bg-white dark:bg-gray-900">
            <div className="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
                <div className="mx-auto max-w-screen-md text-center mb-8 lg:mb-12">
                    <h2 className="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Buy More Credits</h2>
                </div>
                <CreditPricingCards features={features.data} packages={packages.data}></CreditPricingCards>
            </div>
        </section>

    </AuthenticatedLayout>


}
