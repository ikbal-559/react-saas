import { usePage} from "@inertiajs/react";

export default function CreditPricingCards({packages, features}) {
    const { csrf_token } = usePage().props;

    return (
        <section className="bg-gray-900">
            <div className="py-8 px-4">
                <div className="text-center mb-8">
                    <h2 className="mb-4 text-4xl font-extrabold text-white">The more Credits you choose the bigger saving you will make.</h2>
                </div>
                <div className="space-y-8 lg:grid lg:grid-cols-3  lg:space-y-0">
                    {packages.map((item) => (
                        <div
                            key={item.id}
                            className="flex flex-col p-6 mx-auto max-w-lg text-center text-gray-900 bg-white rounded-lg border border-gray-100 shadow dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
                            <h3 className="mb-4 text-2xl font-semibold">{item.name}</h3>
                            <div className="flex justify-center items-baseline my-8">
                                <span className="mr-2 text-3xl font-extrabold">${item.price}</span>
                                <span className="text-gray-500 text-xl dark:text-gray-400">/ {item.credits} Credits</span>
                            </div>
                            <ul role="list" className="mb-8 space-y-4 text-left">
                                {features.map( (feature) => (
                                    <li
                                        key={feature.id}
                                        className="flex items-center space-x-3">
                                        <svg className="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400"
                                             fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                        <span>{feature.name}</span>
                                    </li>
                                ) )}
                            </ul>
                            <form
                                action={route('credit.buy', item)}
                                method="post"
                                className="w-full">
                                <input
                                    type="hidden"
                                    name="_token"
                                    value={csrf_token}
                                    autoComplete="off"

                                ></input>

                                <button type="submit"
                                        className="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-white dark:text-gray-800  dark:focus:ring-primary-900"
                                    >Get Credits</button>
                            </form>
                        </div>
                    ))};
                </div>
            </div>
        </section>
    );
}
