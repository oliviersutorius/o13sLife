import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
    vus: 10,
    duration: '10s',
    thresholds: {
        http_req_failed: ['rate<0.05'],
        http_req_duration: ['p(95)<500'],
    },
};

const BASE_URL = 'http://localhost:8000';

export default function () {
    const res = http.get(`${BASE_URL}/`);

    check(res, {
        'page CV status 200': (r) => r.status === 200,
        'temps de réponse < 500ms': (r) => r.timings.duration < 500,
    });

    sleep(1);
}
