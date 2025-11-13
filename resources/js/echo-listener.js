// Simple listener for AssessmentGraded events
import './bootstrap';

window.addEventListener('DOMContentLoaded', () => {
    if (!window.Echo || !window.Laravel) return;

    const userId = window.Laravel?.user?.id ?? null;
    if (!userId) return;

    window.Echo.private(`user.${userId}`)
        .listen('AssessmentGraded', (payload) => {
            // Simple UI notification; replace with toast library if desired
            alert(`Your assessment #${payload.assessment_id} was graded: ${payload.score}`);
        });
});
