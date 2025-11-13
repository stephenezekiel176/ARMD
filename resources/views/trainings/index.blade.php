@extends('layouts.app')

@section('title', 'Professional Development & Trainings - ' . config('app.name'))

@section('content')
<div class="relative isolate">
    <!-- Header -->
    <div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="text-4xl font-bold">Professional Development & Trainings</h1>
                <p class="mt-4 text-xl text-primary-100">
                    Advance your career with practical assessments, seminars, and industry insights
                </p>
            </div>
        </div>
    </div>

    <!-- Training Categories -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Practical Assessments -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="p-8">
                    <div class="flex items-center mb-6">
                        <div class="flex items-center justify-center w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-lg mr-4">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Practical Assessments</h3>
                            <p class="text-gray-500 dark:text-gray-400">Hands-on evaluation of your skills</p>
                        </div>
                    </div>
                    
                    <div class="prose prose-gray dark:prose-invert max-w-none mb-6">
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            Practical assessments provide real-world scenarios to test and validate your professional competencies. 
                            These evaluations go beyond theoretical knowledge to measure your ability to apply concepts in 
                            actual workplace situations, ensuring you're truly ready for professional challenges.
                        </p>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Real-world Application</h4>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Test your skills in authentic professional scenarios</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Performance Validation</h4>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Demonstrate your capabilities to employers and clients</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Skill Gap Identification</h4>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Identify areas for improvement and targeted development</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">How It Makes You Stand Out</h4>
                        <p class="text-blue-700 dark:text-blue-300 text-sm">
                            Practical assessments provide concrete evidence of your abilities, making your resume and portfolio 
                            more compelling to employers. They demonstrate that you can deliver results, not just understand theory.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Seminars -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="p-8">
                    <div class="flex items-center mb-6">
                        <div class="flex items-center justify-center w-16 h-16 bg-purple-100 dark:bg-purple-900 rounded-lg mr-4">
                            <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Seminars</h3>
                            <p class="text-gray-500 dark:text-gray-400">Interactive learning with experts</p>
                        </div>
                    </div>
                    
                    <div class="prose prose-gray dark:prose-invert max-w-none mb-6">
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            Seminars offer intensive learning experiences led by industry experts and thought leaders. 
                            These interactive sessions provide deep insights into specific topics, networking opportunities, 
                            and access to cutting-edge developments in your field.
                        </p>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Expert Knowledge</h4>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Learn directly from industry leaders and practitioners</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Networking Opportunities</h4>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Connect with peers and professionals in your field</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Current Trends</h4>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Stay updated with the latest industry developments</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
                        <h4 class="font-semibold text-purple-900 dark:text-purple-100 mb-2">How It Makes You Stand Out</h4>
                        <p class="text-purple-700 dark:text-purple-300 text-sm">
                            Seminar participation demonstrates your commitment to continuous learning and professional growth. 
                            It shows employers that you're proactive about staying current and investing in your development.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Interviews -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="p-8">
                    <div class="flex items-center mb-6">
                        <div class="flex items-center justify-center w-16 h-16 bg-green-100 dark:bg-green-900 rounded-lg mr-4">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Professional Interviews</h3>
                            <p class="text-gray-500 dark:text-gray-400">Insights from industry professionals</p>
                        </div>
                    </div>
                    
                    <div class="prose prose-gray dark:prose-invert max-w-none mb-6">
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            Professional interviews provide firsthand insights from experienced practitioners, hiring managers, 
                            and industry leaders. These sessions reveal career pathways, skill requirements, and insider 
                            knowledge that can accelerate your professional development.
                        </p>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Career Insights</h4>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Learn about career paths and advancement opportunities</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Skill Requirements</h4>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Understand what employers really look for in candidates</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Industry Perspectives</h4>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Gain insider knowledge about industry trends and challenges</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                        <h4 class="font-semibold text-green-900 dark:text-green-100 mb-2">How It Makes You Stand Out</h4>
                        <p class="text-green-700 dark:text-green-300 text-sm">
                            Interview insights give you a competitive edge by understanding employer expectations and 
                            industry demands. This knowledge helps you tailor your skills and presentation to match 
                            what organizations truly value.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Industry Standard News -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="p-8">
                    <div class="flex items-center mb-6">
                        <div class="flex items-center justify-center w-16 h-16 bg-orange-100 dark:bg-orange-900 rounded-lg mr-4">
                            <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Industry Standard News</h3>
                            <p class="text-gray-500 dark:text-gray-400">Stay current with industry developments</p>
                        </div>
                    </div>
                    
                    <div class="prose prose-gray dark:prose-invert max-w-none mb-6">
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            Industry standard news keeps you informed about the latest developments, regulations, 
                            technologies, and best practices in your field. Staying current with industry news 
                            demonstrates your professional awareness and adaptability to change.
                        </p>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Market Awareness</h4>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Understand market trends and economic indicators</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Regulatory Updates</h4>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Stay informed about compliance requirements and standards</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Technology Advances</h4>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Keep up with emerging technologies and innovations</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4">
                        <h4 class="font-semibold text-orange-900 dark:text-orange-100 mb-2">How It Makes You Stand Out</h4>
                        <p class="text-orange-700 dark:text-orange-300 text-sm">
                            Industry awareness shows employers that you're engaged and knowledgeable about your field. 
                            It demonstrates strategic thinking and the ability to anticipate and adapt to industry changes.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- How These Trainings Advance Your Career -->
        <div class="mt-16 bg-gradient-to-r from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 rounded-2xl p-8">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white text-center mb-8">
                How Professional Development Makes You Stand Out
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-primary-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Competitive Advantage</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Demonstrate superior knowledge and skills that differentiate you from other candidates
                    </p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-primary-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Career Growth</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Accelerate your career progression with validated skills and industry recognition
                    </p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-primary-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Professional Network</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Build valuable connections with industry leaders and like-minded professionals
                    </p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-primary-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Industry Credibility</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Establish yourself as a knowledgeable and committed professional in your field
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
