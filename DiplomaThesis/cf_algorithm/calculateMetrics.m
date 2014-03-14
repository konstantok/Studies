function [totalRecall , totalPrecision ] = calculateMetrics( Test_Set,Train_Set,PI,N,a)
% 
% =================================
% 
% Univesrity of Patras 
% Computer Engineering and Informatics Department 
% 
% Diploma Thesis 
% Aimed Product Suggestion to Social Network Users 
% 
% Konstantinos Konstantopoulos kkonstanto@ceid.upatras.gr
% 
% =================================
%
    % Calculate Precision and Recall Metrics 
    users = size(Test_Set,2);
    w = 0;
    hit = 0;

    for i=1:users
        eventid_test_set =  find(Test_Set(:,i) > a);

        if length(eventid_test_set) == 0
            recall_overall(i) = 0;
            precision_overall(i) = 0;
            w = w + 1;
            continue;
        end

        eventid_train_set = find(Train_Set(:,i)>0);

        PI(eventid_train_set , i) = -100;

        R = size(eventid_test_set,1);
        
        % Debug
        % PI_size= size(PI,1);
        % Tr_size = size(eventid_train_set,1);
        % for t = 1:Tr_size
        %     for q=1:PI_size
        %         if eventid_train_set(t,i) == PI(q,i)
        %             PI(q,i)= -1;
        %         end
        %     end
        % end

        
        % Sorted_Codes Vector Contains the Events Ids placed at the top of the list 
        [Sorted_PI, Sorted_Codes]=sort(PI(:,1),'descend');
        
        % Contains top-N Events suggested for Each User
         topN_events = Sorted_Codes(1:N); 

         for j=1:R
             for k=1:N
                if eventid_test_set(j) == topN_events(k)
                    hit=hit+1;
                    break;
                end
             end

         end  

         % Metrics for Each User
         recall = hit/R; 
         precision = hit/N ;
         recall_overall(i) = recall; 
         precision_overall(i)= precision;
         hit = 0;

    end


    totalRecall = sum(recall_overall)/(users-w);
    totalPrecision = sum(precision_overall)/(users-w);


end