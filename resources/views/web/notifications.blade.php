@extends('web.template.layout')

@section('title', 'Home')
@section("s-header")
    <script type="module" auther="sooraj">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-app.js";
    import { getDatabase, ref, onValue, query, limitToLast, orderByKey, startAt, endAt,orderByChild,onChildAdded,update,equalTo } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-database.js";



        // Your web app's Firebase configuration
        const firebaseConfig = {
            apiKey:'{{config("global.apiKey")}}',
            authDomain:'{{config("global.authDomain")}}',
            databaseURL:'{{config("global.databaseURL")}}',
            projectId:'{{config("global.projectId")}}',
            storageBucket:'{{config("global.storageBucket")}}',
            messagingSenderId:'{{config("global.messagingSenderId")}}',
            appId:'{{config("global.appId")}}',
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
    const database = getDatabase(app);
    let initialLoadCompleted = false;
    const user_firebase_user_key = '{{Auth::User()->firebase_user_key}}';
    const db_url = `Nottifications/${user_firebase_user_key}/`;

   

    const dbRef = ref(database, db_url);
    const batchSize = 10; // Number of items to load per batch
    let lastKey = null;
    let endReached = false;
    const listContainer = document.getElementById('notification-tbody');
    let addedKeys = [];
    let usedLastKeys = [];
    var readIcon ='';
    
    // Function to render notifications
    function renderNotifications(data) {
        // Convert the data object to an array
        const dataArray = Object.values(data);

        // Reverse the array to display the most recent records first
        dataArray.reverse();

        dataArray.forEach((item) => {
            const notificationItem = document.createElement('a');
            notificationItem.href = `{{url('website/patient-appointment_detail')}}/${item.order_id}`;
            notificationItem.className = "text-reset notification-item";
            if(item.read == 0){
                readIcon = '<span style="color:red;">*</span>';
            }else{
                readIcon = '';
            }
            notificationItem.innerHTML = `
                <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                        <img src="${item.imageURL}" onerror="this.onerror=null; this.src='{{ URL::asset('admin-assets/assets/images/placeholder.jpg') }}'"
                            class="rounded-circle avatar-sm" alt="user-pic">
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted font-size-13 mb-0 float-end">${timeAgo(item.createdAt)}</p>
                        <h6 class="mb-1">${item.title} ${readIcon}</h6>
                        <div>
                            <p class="mb-0">${item.description}</p>
                        </div>
                    </div>
                </div>
            `;
            listContainer.appendChild(notificationItem); // Append new item
        });
    }

    // Initial load of latest items
    console.log(dbRef);
    const initialQuery = query(dbRef, orderByKey(), limitToLast(batchSize));
    onValue(initialQuery, (snapshot) => {
        if(!initialLoadCompleted){
            const data = snapshot.val();
            console.log(data);
            renderNotifications(data);

            // Track the last key for pagination
            const keys = Object.keys(data);
            //keys.reverse();
            //console.log(keys);
            lastKey = keys[0];
            console.log(lastKey);
            keys.forEach(function(it){
                addedKeys.push(it);
                let updates = {};
                updates[`${db_url}${it}/read`] = "1";
                update(ref(database), updates)
                .then(() => {
                    //console.log('All unread notifications have been updated to read.');
                })
                .catch((error) => {
                    //console.error('Error updating notifications:', error);
                });
            });
        }
        console.log("sa");
        initialLoadCompleted=true;
        
    });


    // Load older items on scroll
    window.addEventListener('scroll', () => {
        
        if (endReached) return; // If end of data is reached, do nothing

        const containerHeight = listContainer.clientHeight;
        const scrollOffset = window.pageYOffset;
        const windowHeight = window.innerHeight;

        if (containerHeight - (scrollOffset + windowHeight) < 100) {
            // Fetch older items
           
            if(!usedLastKeys.includes(lastKey)){
                let olderQuery = query(dbRef, orderByKey(), endAt(lastKey), limitToLast(batchSize+1));
                
                usedLastKeys.push(lastKey);
                onValue(olderQuery, (snapshot) => {
                    
                    const data = snapshot.val();
                    
                    //alert(Object.keys(data).length);
                    if (!data || Object.keys(data).length <= 1 ) {
                        endReached = true;
                        return;
                    }

                    // Remove the last item (already rendered)
                    const keys = Object.keys(data);
                    keys.forEach(function(it){
                        addedKeys.push(it);
                        let updates = {};
                        updates[`${db_url}${it}/read`] = "1";
                        update(ref(database), updates)
                        .then(() => {
                            //console.log('All unread notifications have been updated to read.');
                        })
                        .catch((error) => {
                            //console.error('Error updating notifications:', error);
                        });
                    });
                    
                
                    const lastIndex = keys.length - 1;
                    delete data[keys[lastIndex]];

                    // Render older items
                    renderNotifications(data);

                    // Update lastKey for the next pagination
                    keys.reverse();
                    lastKey = keys[lastIndex];
                    console.log(keys);
                    console.log(lastKey);
                    if(Object.keys(data).length <= batchSize){
                        endReached = true;
                    }
                    
                });
            }
            return;
        }
    });

    

    const unreadQuery = query(dbRef, orderByChild('read'), equalTo(0));

    // Perform the update
    

    function timeAgo(timestamp) {
        const parsedDate = parseAndFormatDate(timestamp);
        const now = Date.now();
        const seconds = Math.floor((now - parsedDate) / 1000);

        let interval = Math.floor(seconds / 31536000); // Year interval
        if (interval >= 1) {
            return interval + " year" + (interval > 1 ? "s" : "") + " ago";
        }

        interval = Math.floor(seconds / 2592000); // Month interval
        if (interval >= 1) {
            return interval + " month" + (interval > 1 ? "s" : "") + " ago";
        }

        interval = Math.floor(seconds / 86400); // Day interval
        if (interval >= 1) {
            return interval + " day" + (interval > 1 ? "s" : "") + " ago";
        }

        interval = Math.floor(seconds / 3600); // Hour interval
        if (interval >= 1) {
            return interval + " hour" + (interval > 1 ? "s" : "") + " ago";
        }

        interval = Math.floor(seconds / 60); // Minute interval
        if (interval >= 1) {
            return interval + " minute" + (interval > 1 ? "s" : "") + " ago";
        }

        return Math.floor(seconds) + " second" + (seconds > 1 ? "s" : "") + " ago";
    }

    function parseAndFormatDate(dateString) {
        const parts = dateString.split(' '); // Split date and time
        const datePart = parts[0];
        const timePart = parts[1];
        const [day, month, year] = datePart.split('-');
        const [hours, minutes, seconds] = timePart.split(':');
        const jsMonth = parseInt(month, 10) - 1;
        const parsedDate = new Date(Date.UTC(year, jsMonth, day, hours, minutes, seconds));
        return parsedDate;
    }
    </script>
@stop
@section('content')
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <!-- <div class="main-content"> -->
            <div class="page-content">
                    <div class="container-fluid">

                        <div class="position-relative mb-5">
                            <div class="d-lg-flex">
                                @include('web.profile-sidebar')
                                <!-- end chat-leftsidebar -->
                        
                                <div class="w-100 user-chat mt-4 mt-sm-0 ms-lg-3">
                                    <div class="card">
                                        
                                        <div class="card-body">
                                            <div class="table-responsive" id="notification-tbody">
                                            
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                    <!-- end user chat -->

                            </div>
                    <!-- container-fluid -->
                    </div>
            <!-- </div> -->
            <!-- end main content-->

            
            
           
@endsection

@section("page_script")
@stop
@section('custom_js')
    
@endsection