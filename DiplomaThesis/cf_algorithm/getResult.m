function FR = getResult(RatesArray, SimilaritesArray, a, errorTol, maxIts)
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
    % Final Result
    FR = [];


    for i=1:length( RatesArray(1, :) )
        % Get User's Vector
        w = RatesArray(:, i);
        
        % Normalize User's Vector (if has voted at least 1 item)
        % ---- not necessary ---- 
        %if max(w) ~= 0
        %    w = ( 1/sum(w) ) .* w;
        %end

        P = a .* SimilaritesArray + (1-a) .* ( ones(length(w), 1) * w' );

        % Power Iteration
        P_new = doPowerMethod(P, w, 0, maxIts, errorTol);
        
        % Store Sorted Indeces for each User
        FR = [FR P_new];

        %i

    end

end
