import React from 'react';
import ReactDOM from 'react-dom';
import App from './Pages/App';
import 'bootstrap/dist/css/bootstrap.css';
import './properties.scss';
import rootReducer from "./Reducers";
import { Provider } from 'react-redux';
import thunk from 'redux-thunk';
import { legacy_createStore, applyMiddleware } from 'redux';

// const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;
// const store = legacy_createStore(
//     rootReducer,
//     composeEnhancers(applyMiddleware(thunk))
// );

const store = legacy_createStore(
    rootReducer,
    applyMiddleware(thunk)
);

ReactDOM.render(
    <Provider store={store}>
        <App />
    </Provider>,
    document.getElementById('root')
);
